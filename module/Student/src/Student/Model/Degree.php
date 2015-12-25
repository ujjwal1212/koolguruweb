<?php

namespace Student\Model;

// Add these import statements
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Degree implements InputFilterAwareInterface {

    public $id;
    public $degree_name;    
    public $status;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->degree_name = (isset($data['degree_name'])) ? $data['degree_name'] : null;        
        $this->status = (isset($data['status'])) ? $data['status'] : null;        
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
                        'name' => 'degree_name',
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
                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter Degree Name!'
                                    ),
                                ),
                            ),
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
