<?php

namespace User\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Form Class for User Form
 */
class UserRoleForm extends Form {

    public function __construct($name = null, $systemRoles) {
        // we want to ignore the name passed
        parent::__construct('user_role');

        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'user_role_id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'role_name',
            'attributes' => array(
                'type' => 'text',
                'id' => 'role_name',
                'class' => 'input',
            ),
            'options' => array(
            ),
        ));
        $this->add(array(
            'name' => 'role_code',
            'attributes' => array(
                'type' => 'text',
                'id' => 'role_code',
                'readonly' => 'readonly',
                'class' => 'input',
            ),
            'options' => array(
                'label' => 'ROLE CODE',
                'label_attributes' => array(
                    'class' => 'label'
                ),
            ),
        ));
        
         $this->add(array(
            'name' => 'parent_role',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'parent_role',
            ),
            'options' => array(
                'label' => 'PARENT ROLE',
                'label_attributes' => array(
                    'class' => 'label'
                ),
                'empty_option' => 'Select',
                'value_options' => $systemRoles,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'status',
            'options' => array(
                'label' => 'STATUS',
                'label_attributes' => array(
                    'class' => ''
                ),
                'value_options' => array(
                    'Active' => 'Active',
                    'InActive' => 'InActive',
                ),
            ),
            'attributes' => array(
                'value' => 'Active',
                'id' => 'status'
            ),
        ));


        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Submit',
                'id' => 'submitbutton',
                'class' => 'green-btn big-btn margin-Top10-Btm40',
            ),
        ));
    }

}
