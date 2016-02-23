<?php

namespace Questionarie\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Form Class for User Form
 */
class LevelForm extends Form {

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
            'name' => 'name',
            'attributes' => array(
                'type' => 'text',
                'id' => 'name',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'LEVEL NAME',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'description',
            'attributes' => array(
                'class' => 'input',
                'id'    => 'description',
            ),
            'options' => array(
                'label' => 'DESCRIPTION',
                'label_attributes' => array(
                    'class' => 'label'
                ),
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        
        
        $this->add(array(
            'name' => 'created_date',
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
            'name' => 'updated_date',
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
                'value' => 'Publish',
                'id' => 'submitbutton',
                'class' => 'green-btn big-btn',
            ),
        ));
        
    }

}
