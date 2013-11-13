<?php
/**
 * ScContent (https://github.com/dphn/ScContent)
 *
 * @author    Dolphin <work.dolphin@gmail.com>
 * @copyright Copyright (c) 2013 ScContent
 * @link      https://github.com/dphn/ScContent
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ScContent\Form\Back;

use Zend\InputFilter\InputFilter,
    Zend\Form\Form;

/**
 * @author Dolphin <work.dolphin@gmail.com>
 */
class Article extends Form
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('article');
        $this->setFormSpecification()->setInputSpecification();
    }

    /**
     * @return Article
     */
    protected function setFormSpecification()
    {
        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Title',
                'required'    => 'required',
                'class'       => 'form-control input-lg',
            ),
        ));

        $this->add(array(
            'name' => 'content',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Article content',
                'label_attributes' => array(
                    'class' => 'content-form-label'
                ),
            ),
            'attributes' => array(
                'id' => 'content',
                'class' => 'form-control',
                'rows'  => 14,
            ),
        ));

        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'options' => array(
                'label' => 'Status',
                'value_options' => array(
                    'published' => 'Published',
                    'draft' => 'Draft',
                ),
            ),
            'attributes' => array(
                'id' => 'status',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'save',
            'type' => 'button',
            'options' => array(
                'label' => 'Save',
            ),
            'attributes' => array(
                'type'  => 'submit',
                'class' => 'btn btn-primary',
                'value' => 'save',
            ),
        ));

        return $this;
    }

    /**
     * @return Article
     */
    protected function setInputSpecification()
    {
        $spec = new InputFilter();

        $spec->add(array(
            'name' => 'title',
            'required' => true,
        ));

        $this->setInputFilter($spec);

        return $this;
    }
}
