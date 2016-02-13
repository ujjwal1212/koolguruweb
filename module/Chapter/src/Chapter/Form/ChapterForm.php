<?php

namespace Chapter\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Form Class for User Form
 */
class ChapterForm extends Form {

    public function __construct($name = NULL) {
        // we want to ignore the name passed
        parent::__construct($name);


        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));        
        
                
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'status',
            'options' => array(
                'label' => 'Status',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'isdemo',
            'options' => array(
                'label' => 'IS DEMO',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            )
        ));
        
        $this->add(array(
            'name' => 'code',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));


        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type' => 'text',
                'id' => 'name',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'Chapter Title',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        $this->add(array(
            'name' => 'content',
            'attributes' => array(
                'type' => 'textarea',
                'id' => 'content',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'Description',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));        

        $this->add(array(
            'name' => 'created_at',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'created_by',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'updated_at',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'updated_by',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Submit',
                'id' => 'submitbutton',
                'class' => 'green-btn big-btn',
            ),
        ));
    }

}
