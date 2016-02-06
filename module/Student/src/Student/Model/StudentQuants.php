<?php

namespace Student\Model;

// Add these import statements
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class StudentQuants implements InputFilterAwareInterface {

    public $id;
    public $student_id;    
    public $question_id;
    public $max_marks;
    public $marks_obtain;
    public $created;
    public $option_selected;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->student_id = (isset($data['student_id'])) ? $data['student_id'] : null;
        $this->question_id = (isset($data['question_id'])) ? $data['question_id'] : null;
        $this->max_marks = (isset($data['max_marks'])) ? $data['max_marks'] : null;
        $this->marks_obtain = (isset($data['marks_obtain'])) ? $data['marks_obtain'] : null;
        $this->created = (isset($data['created'])) ? $data['created'] : null;
        $this->option_selected = (isset($data['option_selected'])) ? $data['option_selected'] : null;
               
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
