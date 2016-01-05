<?php

namespace Questionarie\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Question implements InputFilterAwareInterface {

    public $id;
    public $name;
    public $description;
    public $min_marks;
    public $max_marks;
    public $level;
    public $type;
    public $status;
    public $created_date;
    public $created_by;
    public $updated_date;
    public $updated_by;
    protected $inputFilter;

    //protected $_dbAdapter;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->min_marks = (isset($data['min_marks'])) ? $data['min_marks'] : null;
        $this->max_marks = (isset($data['max_marks'])) ? $data['max_marks'] : null;
        $this->level = (isset($data['level'])) ? $data['level'] : null;
        $this->type = (isset($data['type'])) ? $data['type'] : null;
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

            $inputFilter->add($factory->createInput(array(
                        'name' => 'name',
                        'required' => true,
                        'filters' => array(
                            array(
                                'name' => 'StripTags'
                            ),
                            array(
                                'name' => 'StringTrim'
                            )
                        ),
                        'validators' => array(
                            array(
                                'name' => 'NotEmpty',
                                'options' => array(
                                    'messages' => array(
                                        $isEmpty => 'Center Name can not be left blank.'
                                    )
                                )
                            )
                        )
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}
