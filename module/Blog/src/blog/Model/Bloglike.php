<?php

namespace Blog\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Bloglike implements InputFilterAwareInterface {

    public $id;
    public $blog_id;    
    public $is_student; 
    public $status;
    public $user_id;
    public $created_at;    
    protected $inputFilter;

    //protected $_dbAdapter;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->blog_id = (isset($data['blog_id'])) ? $data['blog_id'] : null;             
        $this->is_student = (isset($data['is_student'])) ? $data['is_student'] : null; 
        $this->created_at = (isset($data['created_at'])) ? $data['created_at'] : null;
        $this->user_id = (isset($data['user_id'])) ? $data['user_id'] : null;        
        $this->status = (isset($data['status'])) ? $data['status'] : null;        
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
                        'name' => 'title',
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
                                        $isEmpty => 'Title can not be left blank.'
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
