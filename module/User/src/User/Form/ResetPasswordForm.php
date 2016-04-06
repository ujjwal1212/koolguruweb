<?php

namespace User\Form;

use Zend\Form\Form;

class ResetPasswordForm extends Form {

    public function __construct($name = null) {
        // we want to ignore the name passed
        parent::__construct('resetpassword');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'oldpassword',
            'attributes' => array(
                'class' => 'text-box input-sm input',
                'type' => 'password',
                'id' => 'oldpassword',
                'placeholder' => '********',
            )
        ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'class' => 'text-box input-sm input',
                'type' => 'password',
                'id' => 'password',
                'placeholder' => '********',
            )
        ));


        $this->add(array(
            'name' => 'repassword',
            'attributes' => array(
                'class' => 'text-box input-sm input',
                'type' => 'password',
                'id' => 'repassword',
                'placeholder' => '********',
            ),
            'validators' => array(
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'password',
                    ),
                ),
            ),
        ));



        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Reset Password',
                'class' => 'submit big-btn green-btn',
                'id' => 'submitbutton',
            ),
        ));
    }

}
