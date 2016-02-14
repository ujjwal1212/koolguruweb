<?php

namespace Subject\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Category implements InputFilterAwareInterface {

    public $id;
    public $title;
    public $description;
    public $status;
    public $created_date;
    public $created_by;
    public $updated_date;
    public $updated_by;
    protected $inputFilter;

    //protected $_dbAdapter;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->created_date = (isset($data['created_date'])) ? $data['created_date'] : null;
        $this->created_by = (isset($data['created_by'])) ? $data['created_by'] : null;
        $this->updated_date = (isset($data['updated_date'])) ? $data['updated_date'] : null;
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
