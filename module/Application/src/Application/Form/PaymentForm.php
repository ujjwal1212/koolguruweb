<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Form Class for User Form
 */
class PaymentForm extends Form {

    public function __construct($name = NULL) {
        // we want to ignore the name passed
        parent::__construct($name);


        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->add(array(
            'name' => 'txnid',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'amount',
            'attributes' => array(
                'type' => 'text',
                'id' => 'amount',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'Amount',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        $this->add(array(
            'name' => 'firstname',
            'attributes' => array(
                'type' => 'text',
                'id' => 'firstname',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'First Name',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'text',
                'id' => 'email',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'Email',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        $this->add(array(
            'name' => 'phone',
            'attributes' => array(
                'type' => 'text',
                'id' => 'phone',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'Phone',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));

        $this->add(array(
            'name' => 'productinfo',
            'attributes' => array(
                'type' => 'textarea',
                'id' => 'productinfo',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'Product Info',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        $this->add(array(
            'name' => 'surl',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'furl',
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
