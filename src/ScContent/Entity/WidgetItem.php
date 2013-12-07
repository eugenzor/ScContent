<?php
/**
 * ScContent (https://github.com/dphn/ScContent)
 *
 * @author    Dolphin <work.dolphin@gmail.com>
 * @copyright Copyright (c) 2013 ScContent
 * @link      https://github.com/dphn/ScContent
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ScContent\Entity;

/**
 * @author Dolphin <work.dolphin@gmail.com>
 */
class WidgetItem extends AbstractEntity
{
    /**
     * @var integer | null
     */
    protected $id;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $region = '';

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var integer
     */
    protected $position = 0;

    /**
     * @param integer $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $region
     * @return void
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string | array $options
     * @return void
     */
    public function setOptions($options)
    {
        if (is_string($options)) {
            $options = unserialize($options);
        }
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param integer $position
     * @return void
     */
    public function setPosition($position)
    {
        $this->position = (int) $position;
    }

    /**
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        if (isset($this->options['display_name'])) {
            return $this->options['display_name'];
        }
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function hasDescription()
    {
        return isset($this->options['description']);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        if (isset($this->options['description'])) {
            return $this->options['description'];
        }
    }
}