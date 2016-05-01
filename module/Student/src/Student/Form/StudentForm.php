<?php

namespace Student\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Form Class for User Form
 */
class StudentForm extends Form {

    public function __construct($name = null, $degreelist, $stateList) {
        //asd($degreelist);
        // we want to ignore the name passed
        parent::__construct($name);
        $stateList = array(
            'AP' => 'Andhra Pradesh',
            'AR' => 'Arunachal Pradesh',
            'AS' => 'Assam',
            'BR' => 'Bihar',
            'CT' => 'Chhattisgarh',
            'GA' => 'Goa',
            'GJ' => 'Gujarat',
            'HR' => 'Haryana',
            'HP' => 'Himachal Pradesh',
            'JK' => 'Jammu & Kashmir',
            'JH' => 'Jharkhand',
            'KA' => 'Karnataka',
            'KL' => 'Kerala',
            'MP' => 'Madhya Pradesh',
            'MH' => 'Maharashtra',
            'MN' => 'Manipur',
            'ML' => 'Meghalaya',
            'MZ' => 'Mizoram',
            'NL' => 'Nagaland',
            'OR' => 'Odisha',
            'PB' => 'Punjab',
            'RJ' => 'Rajasthan',
            'SK' => 'Sikkim',
            'TN' => 'Tamil Nadu',
            'TR' => 'Tripura',
            'UK' => 'Uttarakhand',
            'UP' => 'Uttar Pradesh',
            'WB' => 'West Bengal',
            'AN' => 'Andaman & Nicobar',
            'CH' => 'Chandigarh',
            'DN' => 'Dadra and Nagar Haveli',
            'DD' => 'Daman & Diu',
            'DL' => 'Delhi',
            'LD' => 'Lakshadweep',
            'PY' => 'Puducherry',
        );
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'student_id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'fname',
            'attributes' => array(
                'type' => 'text',
                'id' => 'fname',
                'class' => 'input'
            ),
            'options' => array(
            ),
        ));
        $this->add(array(
            'name' => 'mname',
            'attributes' => array(
                'type' => 'text',
                'id' => 'mname',
                'class' => 'input'
            ),
            'options' => array(
            ),
        ));

        $this->add(array(
            'name' => 'lname',
            'attributes' => array(
                'type' => 'text',
                'id' => 'lname',
                'class' => 'input'
            ),
            'options' => array(
            ),
        ));

        $this->add(array(
            'type' => 'Select',
            'name' => 'sex',
            'options' => array(
                'label' => 'Sex',
                'empty_option' => 'Select one',
                'value_options' => array('Male', 'Female')
            ),
            'attributes' => array(
                'id' => 'sex',
//                'class' => 'form-select',
            ),
        ));

        $this->add(array(
            'name' => 'father_occupation',
            'attributes' => array(
                'type' => 'text',
                'id' => 'father_occupation',
                'class' => 'input'
            ),
            'options' => array(
            ),
        ));

        $this->add(array(
            'type' => 'Select',
            'name' => 'highest_degree',
            'options' => array(
                'label' => 'Highest Degree',
                'empty_option' => 'Select one',
                'value_options' => $degreelist
            ),
            'attributes' => array(
                'id' => 'highest_degree',
//                'class' => 'form-select',
            ),
        ));

        $this->add(array(
            'type' => 'Select',
            'name' => 'native_state',
            'options' => array(
                'label' => 'Native State',
                'empty_option' => 'Select one',
                'value_options' => $stateList
            ),
            'attributes' => array(
                'id' => 'native_state',
//                'class' => 'form-select',
            ),
        ));

        $currentyear = date('Y', time());
        $completionyear = array();
        for ($i = 1970; $i <= $currentyear; $i++) {
            $completionyear[] = $i;
        }

        $this->add(array(
            'type' => 'Select',
            'name' => 'completion_year',
            'options' => array(
                'label' => 'Select one',
                'empty_option' => 'Completion year',
                'value_options' => $completionyear
            ),
            'attributes' => array(
                'id' => 'completion_year',
//                'class' => 'form-select',
            ),
        ));



        $this->add(array(
            'name' => 'city',
            'attributes' => array(
                'type' => 'text',
                'id' => 'city',
                'class' => 'input'
            ),
            'options' => array(
            ),
        ));

        $this->add(array(
            'name' => 'mobile',
            'attributes' => array(
                'type' => 'text',
                'id' => 'mobile',
                'class' => 'input'
            ),
            'options' => array(
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'text',
                'id' => 'email',
                'class' => 'input'
            ),
            'options' => array(
            ),
        ));



        $this->add(array(
            'name' => 'regsubmit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Submit',
                'id' => 'regsubmit',
                'class' => 'green-btn big-btn margin-Top10-Btm40',
            ),
        ));
    }

}
