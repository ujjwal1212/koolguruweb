<?php

namespace Package\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Form Class for User Form
 */
class PackageForm extends Form {

    public function __construct($name = NULL, $courseList) {
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
                'accept' => 'image/*'
            ),
            'options' => array(
                'label' => 'UPLOAD IMAGE',
                'label_attributes' => array(
                    'class' => 'label'
                ),
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
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'description',
            'attributes' => array(
                'class' => 'input',
                'id' => 'description',
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
            'type' => 'text',
            'name' => 'price',
            'attributes' => array(
                'class' => 'input',
                'id' => 'price',
            ),
            'options' => array(
                'label' => 'PRICE',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'duration',
            'attributes' => array(
                'class' => 'input',
                'id' => 'duration',
            ),
            'options' => array(
                'label' => 'DURATION',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'relevant_for',
            'attributes' => array(
                'class' => 'input',
                'id' => 'relevant_for',
            ),
            'options' => array(
                'label' => 'RELEVANT FOR',
                'label_attributes' => array(
                    'class' => 'label'
                ),
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'advantage',
            'attributes' => array(
                'class' => 'input',
                'id' => 'advantage',
            ),
            'options' => array(
                'label' => 'ADVANTAGE',
                'label_attributes' => array(
                    'class' => 'label'
                ),
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'whatuserget',
            'attributes' => array(
                'class' => 'input',
                'id' => 'whatuserget',
            ),
            'options' => array(
                'label' => 'WHAT USER GETS',
                'label_attributes' => array(
                    'class' => 'label'
                ),
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'ff_classroom',
            'attributes' => array(
                'class' => 'input',
                'id' => 'ff_classroom',
            ),
            'options' => array(
                'label' => 'FACE TO FACE CLASSROOM',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));

        $this->add(array(
            'type' => 'Select',
            'name' => 'course_id',
            'options' => array(
                'label' => 'SELECT COURSE',
                'label_attributes' => array(
                    'class' => 'label'
                ),
                'empty_option' => 'Select',
                'value_options' => $courseList,
            ),
            'attributes' => array(
                'id' => 'course_id',
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

        $this->add(array(
            'name' => 'next',
            'attributes' => array(
                'type' => 'button',
                'value' => '',
                'id' => 'next',
                'class' => 'next-step'
            ),
        ));
        $this->add(array(
            'name' => 'previous',
            'attributes' => array(
                'type' => 'button',
                'value' => '',
                'id' => 'previous',
                'class' => 'prev-step'
            ),
        ));
    }

}
