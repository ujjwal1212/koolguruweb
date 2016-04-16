<?php

namespace Subject\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Form Class for User Form
 */
class SubjectForm extends Form {

    public function __construct($name = NULL,$courselist) {
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
            'name' => 'image_path',
            'attributes' => array(
                'type' => 'file',
                'id' => 'image_path',
                'class' => 'input',
                'accept'=>'image/*'
            ),
            'options' => array(
                'label' => 'UPLOAD IMAGE',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        
        $this->add(array(
            'type' => 'Select',
            'name' => 'course_id',
            'options' => array(
                'label' => 'SELECT Course',
                'label_attributes' => array(
                    'class' => 'label'
                ),
                'empty_option' => 'Select',
                'value_options' => $courselist,
            ),
            'attributes' => array(
                'id' => 'course_id',
            ),
        ));
        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type' => 'text',
                'id' => 'title',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'SUBJECT NAME',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));


        $this->add(array(
            'type' => 'text',
            'name' => 'code',
            'attributes' => array(
                'class' => 'input',
                'id' => 'code',
            ),
            'options' => array(
                'label' => 'CODE',
                'label_attributes' => array(
                    'class' => 'label'
                ),
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
//        $this->add(array(
//            'type' => 'Zend\Form\Element\Checkbox',
//            'name' => 'isdemo',
//            'options' => array(
//                'label' => 'IS DEMO',
//                'label_attributes' => array(
//                    'class' => 'label'
//                ),
//            )
//        ));

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
                'value' => 'Publish',
                'id' => 'submitbutton',
                'class' => 'green-btn big-btn',
            ),
        ));
    }

}
