<?php

namespace Student\Model;

// Add these import statements
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class CarrierAnswers implements InputFilterAwareInterface {

    public $id;
    public $student_id;    
    public $question_id;
    public $answer;
    
    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->student_id = (isset($data['student_id'])) ? $data['student_id'] : null;
        $this->question_id = (isset($data['question_id'])) ? $data['question_id'] : null;
        $this->answer = (isset($data['answer'])) ? $data['answer'] : null;
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
