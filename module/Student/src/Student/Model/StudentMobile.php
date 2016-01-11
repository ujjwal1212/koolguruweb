<?php

namespace Student\Model;

// Add these import statements
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class StudentMobile implements InputFilterAwareInterface {

    public $id;
    public $mobile;    
    public $isregistered;
    public $student_id;
    public $created;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->mobile = (isset($data['mobile'])) ? $data['mobile'] : null;        
        $this->isregistered = (isset($data['isregistered'])) ? $data['isregistered'] : null;   
        $this->student_id = (isset($data['student_id'])) ? $data['student_id'] : null;  
        $this->created = (isset($data['created'])) ? $data['created'] : null;  
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
