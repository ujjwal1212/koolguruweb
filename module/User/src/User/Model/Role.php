<?php

namespace User\Model;

// Add these import statements
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Role implements InputFilterAwareInterface {

    public $rid;
    public $role_name;
    public $role_code;
    public $status;
    public $parent_role_code;
    protected $inputFilter;                       // <-- Add this variable

    public function exchangeArray($data) {
        $this->rid = (isset($data['rid'])) ? $data['rid'] : null;
        $this->role_name = (isset($data['role_name'])) ? $data['role_name'] : null;
        $this->role_code = (isset($data['role_code'])) ? $data['role_code'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->parent_role_code = (isset($data['parent_role_code'])) ? $data['parent_role_code'] : null;
    }

    // Add content to these methods:
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                        'name' => 'role_name',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'NotEmpty',
                                'options' => array(
                                    'messages' => array(
                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter Role Name!'
                                    ),
                                ),
                            ),
//                    array(
//                    'name' => 'regex',
//                    'options' => array(
//                        // 'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/',
//                        'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,}$/',
//                        'messages' => array(
//                            \Zend\Validator\Regex::NOT_MATCH => 'Password should contain at least one digit,
//                                                                 at least one lower case,
//                                                                 at least one upper case,
//                                                                 and at least one special character.',
//                        ),
//                    ),
//                ),
                        ),
            )));



            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    // Add the following method:
    public function getArrayCopy() {
        return get_object_vars($this);
    }

}
