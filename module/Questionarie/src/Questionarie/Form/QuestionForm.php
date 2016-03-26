<?php

namespace Questionarie\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Form Class for User Form
 */
class QuestionForm extends Form {

    public function __construct($name = NULL, $levelList,$categoryList) {
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
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'class' => 'input ckeditor',
                'id' => 'name',
            ),
            'options' => array(
                'label' => 'QUESTION NAME',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));


        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'description',
            'attributes' => array(
                'class' => 'input ckeditor',
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
            'name' => 'min_marks',
            'attributes' => array(
                'type' => 'text',
                'id' => 'min_marks',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'MINIMUM MARKS',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        $this->add(array(
            'name' => 'max_marks',
            'attributes' => array(
                'type' => 'text',
                'id' => 'max_marks',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'MAXIMUM MARKS',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        $this->add(array(
            'type' => 'Select',
            'name' => 'level',
            'options' => array(
                'label' => 'LEVEL',
                'label_attributes' => array(
                    'class' => 'label'
                ),
                'empty_option' => 'Select',
                'value_options' => $levelList
            ),
            'attributes' => array(
                'id' => 'level',
            ),
        ));

        $this->add(array(
            'type' => 'Select',
            'name' => 'type',
            'options' => array(
                'label' => 'QUESTION TYPE',
                'label_attributes' => array(
                    'class' => 'label'
                ),
                'empty_option' => 'Select',
                'value_options' => array('MULTI CHOICE ONE ANSWER', 'MULTI CHOICE MULTIPLE ANSWER')
            ),
            'attributes' => array(
                'id' => 'type',
            ),
        ));
        
        $this->add(array(
            'type' => 'Select',
            'name' => 'category_id',
            'options' => array(
                'label' => 'QUESTION CATEGORY',
                'label_attributes' => array(
                    'class' => 'label'
                ),
                'empty_option' => 'Select',
                'value_options' => $categoryList
            ),
            'attributes' => array(
                'id' => 'category_id',
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
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'question_desc',
            'attributes' => array(
                'class' => 'input',
                'id' => 'question_desc',
            ),
            'options' => array(
                'label' => 'QUESTION DESCRIPTION',
                'label_attributes' => array(
                    'class' => 'label'
                ),
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'is_correct',
            'options' => array(
                'label' => 'Status',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
            'attributes' => array(
                'id' => 'is_correct',
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
