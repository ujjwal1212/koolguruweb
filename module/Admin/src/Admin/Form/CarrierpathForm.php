<?php

namespace Admin\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Form Class for User Form
 */
class CarrierpathForm extends Form {

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
                'label' => 'Carrier Path',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        $this->add(array(
            'name' => 'msg',
            'attributes' => array(
                'type' => 'text',
                'id' => 'msg',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'Default Message',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'min_verbal_perc',
            'attributes' => array(
                'type' => 'text',
                'id' => 'min_verbal_perc',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'Minimum Verbal Ability Percentage',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'max_verbal_perc',
            'attributes' => array(
                'type' => 'text',
                'id' => 'max_verbal_perc',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'Maximum Verbal Ability Percentage',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'min_quant_perc',
            'attributes' => array(
                'type' => 'text',
                'id' => 'min_quant_perc',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'Minimum Quantitative Ability Percentage',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'max_quant_perc',
            'attributes' => array(
                'type' => 'text',
                'id' => 'max_quant_perc',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'Maximum Quantitative Ability Percentage',
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
                'value' => 'Submit',
                'id' => 'submitbutton',
                'class' => 'green-btn big-btn',
            ),
        ));
    }

}
