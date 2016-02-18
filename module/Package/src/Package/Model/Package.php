<?php

namespace Package\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Package implements InputFilterAwareInterface {

    public $id;
    public $title;
    public $code;
    public $description;
    public $image_path;
    public $price;
    public $duration;
    public $relevant_for;
    public $advantage;
    public $ff_classroom;
    public $status;
    public $created_at;
    public $created_by;
    public $updated_at;
    public $updated_by;
    protected $inputFilter;

    //protected $_dbAdapter;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->code = (isset($data['code'])) ? $data['code'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->image_path = (isset($data['image_path'])) ? $data['image_path'] : null;
        $this->price = (isset($data['price'])) ? $data['price'] : null;
        $this->duration = (isset($data['duration'])) ? $data['duration'] : null;
        $this->relevant_for = (isset($data['relevant_for'])) ? $data['relevant_for'] : null;
        $this->advantage = (isset($data['advantage'])) ? $data['advantage'] : null;
        $this->ff_classroom = (isset($data['ff_classroom'])) ? $data['ff_classroom'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->created_at = (isset($data['created_at'])) ? $data['created_at'] : null;
        $this->created_by = (isset($data['created_by'])) ? $data['created_by'] : null;
        $this->updated_at = (isset($data['updated_at'])) ? $data['updated_at'] : null;
        $this->updated_by = (isset($data['updated_by'])) ? $data['updated_by'] : null;
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
