<?php

namespace Quiz\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Quiz implements InputFilterAwareInterface {

    public $id;
    public $subject_id;
    public $title;
    public $status;
    public $code;
    public $description;
    public $pass_percentage;
    public $start_time;
    public $end_time;    
    public $created_at;
    public $created_by;
    public $updated_at;
    public $updated_by;
    public $chapter_id;
    protected $inputFilter;

    //protected $_dbAdapter;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->subject_id = (isset($data['subject_id'])) ? $data['subject_id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->code = (isset($data['code'])) ? $data['code'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->pass_percentage = (isset($data['pass_percentage'])) ? $data['pass_percentage'] : null;
        $this->start_time = (isset($data['start_time'])) ? $data['start_time'] : null;
        $this->end_time = (isset($data['end_time'])) ? $data['end_time'] : null; 
        $this->created_at = (isset($data['created_at'])) ? $data['created_at'] : null;
        $this->created_by = (isset($data['created_by'])) ? $data['created_by'] : null;
        $this->updated_at = (isset($data['updated_at'])) ? $data['updated_at'] : null;
        $this->updated_by = (isset($data['updated_by'])) ? $data['updated_by'] : null;
        $this->chapter_id = (isset($data['chapter_id'])) ? $data['chapter_id'] : null;
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
            //$invalidEmail = \Zend\Validator\EmailAddress::INVALID_FORMAT;

            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}
