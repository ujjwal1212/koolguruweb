<?php

namespace Admin\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Form Class for User Form
 */
class CourseForm extends Form {

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
            'name' => 'status',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'image_path',
            'attributes' => array(
                'type' => 'hidden',
            ),
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
                'label' => 'Course Title',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'type' => 'textarea',
                'id' => 'description',
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