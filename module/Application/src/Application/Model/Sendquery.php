<?php

namespace Application\Model;

// Add these import statements
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Sendquery implements InputFilterAwareInterface {

    public $id;
    public $name;    
    public $mobile;
    public $email;
    public $message;
    public $createat;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;     
        $this->mobile = (isset($data['mobile'])) ? $data['mobile'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->message = (isset($data['message'])) ? $data['message'] : null;
        $this->createat = (isset($data['createat'])) ? $data['createat'] : null;
    }

    // Add content to these methods:
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    // Add the following method:
    public function getArrayCopy() {
        return get_object_vars($this);
    }

}
