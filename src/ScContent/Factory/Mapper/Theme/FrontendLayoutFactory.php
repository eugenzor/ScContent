<?php
/**
 * ScContent (https://github.com/dphn/ScContent)
 *
 * @author    Dolphin <work.dolphin@gmail.com>
 * @copyright Copyright (c) 2013-2014 ScContent
 * @link      https://github.com/dphn/ScContent
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ScContent\Factory\Mapper\Theme;

use ScContent\Mapper\Theme\FrontendLayoutMapper,
    //
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\FactoryInterface;

/**
 * @author Dolphin <work.dolphin@gmail.com>
 */
class FrontendLayoutFactory implements FactoryInterface
{
    /**
     * @param  \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \ScContent\Mapper\Theme\FrontendLayoutMapper
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get('ScDb.Adapter');
        $proxy = $serviceLocator->get('ScService.Theme.FrontendRegionsProxy');

        $mapper = new FrontendLayoutMapper($adapter, $proxy);

        return $mapper;
    }
}
