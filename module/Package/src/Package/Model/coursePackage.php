<?php

namespace Package\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class coursePackage implements InputFilterAwareInterface {

    public $id;
    public $package_id;
    public $course_id;
    public $created_at;
    public $created_by;
    public $updated_at;
    public $updated_by;
    protected $inputFilter;

    //protected $_dbAdapter;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->package_id = (isset($data['package_id'])) ? $data['package_id'] : null;
        $this->course_id = (isset($data['course_id'])) ? $data['course_id'] : null;
        $this->created_at = (isset($data['created_at'])) ? $data['created_at'] : null;
        $this->created_by = (isset($data['created_by'])) ? $data['created_by'] : null;
        $this->updated_at = (isset($data['updated_at'])) ? $data['updated_at'] : null;
        $this->updated_by = (isset($data['updated_by'])) ? $data['updated_by'] : null;
    }

    // Add the following method:
    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

//    public function setDbAdapter($dbAdapter) {
//        $this->_dbAdapter = $dbAdapter;
//    }
//
//    public function getDbAdapter() {
//        return $this->_dbAdapter;
//    }

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
