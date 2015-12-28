<?php

namespace Student\Model;

// Add these import statements
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class StudentStatus implements InputFilterAwareInterface {

    public $id;
    public $student_id;    
    public $registration_status;
    public $verbal_reg_status;
    public $marks_obtain_verbal;
    public $quant_status;
    public $marks_obtain_quant;
    public $quant_reg_created;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->student_id = (isset($data['student_id'])) ? $data['student_id'] : null;
        $this->registration_status = (isset($data['registration_status'])) ? $data['registration_status'] : null;
        $this->verbal_reg_status = (isset($data['verbal_reg_status'])) ? $data['verbal_reg_status'] : null;
        $this->marks_obtain_verbal = (isset($data['marks_obtain_verbal'])) ? $data['marks_obtain_verbal'] : null;
        $this->quant_status = (isset($data['quant_status'])) ? $data['quant_status'] : null;
        $this->quant_reg_created = (isset($data['quant_reg_created'])) ? $data['quant_reg_created'] : null;
               
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
