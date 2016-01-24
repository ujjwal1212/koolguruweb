<?php

namespace Student\Model;

// Add these import statements
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Student implements InputFilterAwareInterface {
    public $id;
    public $created;
    public $updated;
    public $fname;
    public $mname;
    public $lname;
    public $sex;
    public $father_occupation;
    public $highest_degree;
    public $completion_year;
    public $native_state;
    public $city;
    public $mobile;
    public $email;
    public $password;
    public $status;
    public $isprofilecompleted;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->created = (isset($data['created'])) ? $data['created'] : null; 
        $this->updated = (isset($data['updated'])) ? $data['updated'] : null; 
        $this->fname = (isset($data['fname'])) ? $data['fname'] : null; 
        $this->mname = (isset($data['mname'])) ? $data['mname'] : null; 
        $this->lname = (isset($data['lname'])) ? $data['lname'] : null; 
        $this->sex = (isset($data['sex'])) ? $data['sex'] : null; 
        $this->father_occupation = (isset($data['father_occupation'])) ? $data['father_occupation'] : null; 
        $this->highest_degree = (isset($data['highest_degree'])) ? $data['highest_degree'] : null; 
        $this->completion_year = (isset($data['completion_year'])) ? $data['completion_year'] : null; 
        $this->native_state = (isset($data['native_state'])) ? $data['native_state'] : null; 
        $this->city = (isset($data['city'])) ? $data['city'] : null; 
        $this->mobile = (isset($data['mobile'])) ? $data['mobile'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->password = (isset($data['password'])) ? $data['password'] : null; 
        $this->status = (isset($data['status'])) ? $data['status'] : null; 
        $this->isprofilecompleted = (isset($data['isprofilecompleted'])) ? $data['isprofilecompleted'] : null;
    }

    // Add content to these methods:
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                        'name' => 'state_name',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'NotEmpty',
                                'options' => array(
                                    'messages' => array(
                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter Degree Name!'
                                    ),
                                ),
                            ),
                        ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    // Add the following method:
    public function getArrayCopy() {
        return get_object_vars($this);
    }

}
