<?php

namespace Student\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Carrierpath implements InputFilterAwareInterface {

    public $id;
    public $name;
    public $msg;
    public $min_verbal_perc;
    public $max_verbal_perc;
    public $min_quant_perc;
    public $max_quant_perc;    
    public $created_date;
    public $created_by;
    public $updated_date;
    public $updated_by;
    protected $inputFilter;

    //protected $_dbAdapter;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->msg = (isset($data['msg'])) ? $data['msg'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;        
        $this->min_verbal_perc = (isset($data['min_verbal_perc'])) ? $data['min_verbal_perc'] : null;
        $this->max_verbal_perc = (isset($data['max_verbal_perc'])) ? $data['max_verbal_perc'] : null;
        $this->min_quant_perc = (isset($data['min_quant_perc'])) ? $data['min_quant_perc'] : null;
        $this->max_quant_perc = (isset($data['max_quant_perc'])) ? $data['max_quant_perc'] : null;        
        $this->created_date = (isset($data['created_date'])) ? $data['created_date'] : null;
        $this->created_by = (isset($data['created_by'])) ? $data['created_by'] : null;
        $this->updated_date = (isset($data['updated_date'])) ? $data['updated_date'] : null;
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
                                        $isEmpty => 'Name can not be left blank.'
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
