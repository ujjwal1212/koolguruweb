<?php

namespace Student\Model;

use Zend\Db\TableGateway\TableGateway;

use Student\Model\StudentVerbal;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
use Zend\Session\Container;

class StudentQuantsTable {

    protected $tableGateway;
    protected $adapter;
    public $table = 'student_reg_quant_ability';

    public function __construct(TableGateway $tableGateway) {       
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }
    
    
    public function saveStudentQuantsDetail($studentDet){        
        $studentid = $studentDet['student_id'];
        unset($studentDet['student_id']);
        unset($studentDet['quantsubmit']);
        unset($studentDet['marks_total_quant']);
        foreach($studentDet as $key => $det){
            $split = explode('~',$det);
            $user_data = array(            
                'student_id' => $studentid,
                'question_id' => $key,
                'max_marks' => $split[2],
                'marks_obtain' => $split[0],
                'created' => time(),
                'option_selected' => $split[1],            
            );           
            $this->tableGateway->insert($user_data);
        }
        $id = $this->tableGateway->lastInsertValue;
        return $id;
    }
}
