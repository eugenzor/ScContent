<?php
/**
 * ScContent (https://github.com/dphn/ScContent)
 *
 * @author    Dolphin <work.dolphin@gmail.com>
 * @copyright Copyright (c) 2013 ScContent
 * @link      https://github.com/dphn/ScContent
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ScContent\Service\Installation;

use ScContent\Migration\SchemaInterface,
    ScContent\Exception\InvalidArgumentException,
    ScContent\Exception\DomainException,
    ScContent\Exception\IoCException,
    //
    Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    //
    Exception;

/**
 * @author Dolphin <work.dolphin@gmail.com>
 */
class MigrationService extends AbstractInstallationService implements
    ServiceLocatorAwareInterface
{
    /**
     * @var Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @const string
     */
    const MigrationFailed = 'Migration failed.';

    /**
     * @var array
     */
    protected $errorMessages = array(
        self::MigrationFailed => 'Migration for the scheme %s is failed.'
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        // Before migration clear all session data
        if (! session_id()) {
            session_start();
        }
        session_unset();
        session_destroy();
    }

    /**
     * @param Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @throws ScContent\Exception\IoCException
     * @return Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        if (! $this->serviceLocator instanceof ServiceLocatorInterface) {
            throw new IoCException('The Service Locator was not set.');
        }
        return $this->serviceLocator;
    }

    /**
     * @param array $options
     * @throws ScContent\Exception\InvalidArgumentException
     * @throws ScContent\Exception\DomainException
     * @return boolean
     */
    public function process($options)
    {
        if (! isset($options['schema'])) {
            throw new InvalidArgumentException("Missing option 'schema'.");
        }
        $serviceLocator = $this->getServiceLocator();
        try {
            $schema = $serviceLocator->get($options['schema']);
        } catch (Exception $e) {
            throw new DomainException(sprintf(
                "Unable to get the schema '%s'.",
                $options['schema']
            ), null, $e);
        }
        if (! $schema instanceof SchemaInterface) {
            throw new DomainException(sprintf(
                "The scheme '%s' must implement the interface 'ScContent\Migration\SchemaInterface'.",
                $options['schema']
            ));
        }
        try {
            $schema->up();
        } catch (Exception $e) {
            $this->setValue($options['schema']);
            $this->error(self::MigrationFailed);
            return false;
        }
        return true;
    }
}
