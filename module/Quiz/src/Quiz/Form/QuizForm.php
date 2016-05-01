<?php

namespace Quiz\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Form Class for User Form
 */
class QuizForm extends Form {

    public function __construct($name = NULL, $sublist) {
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
            'type' => 'Select',
            'name' => 'subject_id',
            'options' => array(
                'label' => 'SELECT Subject',
                'label_attributes' => array(
                    'class' => 'label'
                ),
                'empty_option' => 'Select',
                'value_options' => $sublist,
            ),
            'attributes' => array(
                'id' => 'subject_id',
            ),
        ));
        
        $chapters = array(); 
        $this->add(array(
            'type' => 'Select',
            'name' => 'chapter_id',
            'options' => array(
                'label' => 'SELECT Chapter',
                'label_attributes' => array(
                    'class' => 'label'
                ),
                'empty_option' => 'Select',
                'value_options' => $chapters,
            ),
            'attributes' => array(
                'id' => 'chapter_id',
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
            'name' => 'pass_percentage',
            'attributes' => array(
                'class' => 'input',
                'id' => 'pass_percentage',
            ),
            'options' => array(
                'label' => 'Pass percentage',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'start_time',
            'attributes' => array(
                'class' => 'input',
                'id' => 'start_time',
            ),
            'options' => array(
                'label' => 'Start time',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        
        $this->add(array(
            'type' => 'text',
            'name' => 'end_time',
            'attributes' => array(
                'class' => 'input',
                'id' => 'end_time',
            ),
            'options' => array(
                'label' => 'End time',
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
                'value' => 'Publish',
                'id' => 'submitbutton',
                'class' => 'green-btn big-btn',
            ),
        ));
    }

}
