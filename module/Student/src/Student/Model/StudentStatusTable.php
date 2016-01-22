<?php

namespace Student\Model;

use Zend\Db\TableGateway\TableGateway;

use Student\Model\StudentStatus;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
use Zend\Session\Container;

class StudentStatusTable {

    protected $tableGateway;
    protected $adapter;
    public $table = 'student_status';

    public function __construct(TableGateway $tableGateway) {       
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }
    
    
    public function updateVerbalStatus($data,$studentId){       
        $user_data = array();
        $user_data['verbal_reg_status'] = $data['verbal_reg_status'];
        $user_data['marks_obtain_verbal'] = $data['marks_obtain_verbal'];
        $user_data['verbal_reg_created'] = time();
        $user_data['marks_total_verbal'] = $data['marks_total_verbal'];
        $user_data['verbal_perc'] = $data['verbal_perc'];
        $this->tableGateway->update($user_data, array('student_id' => $studentId));
        return $studentId;
    }
    
    public function updateQuantStatus($data,$studentId){
        $user_data = array();
        $user_data['quant_status'] = $data['quant_status'];
        $user_data['marks_obtain_quant'] = $data['marks_obtain_quant'];        
        $user_data['quant_reg_created'] = time();        
        $user_data['marks_total_quant'] = $data['marks_total_quant'];
        $user_data['quant_perc'] = $data['quant_perc'];        
        $this->tableGateway->update($user_data, array('student_id' => $studentId));
        return $studentId;
    }
    
    public function createStudentStatus($data,$studentId){       
        $user_data = array();
        $user_data['student_id'] = $studentId;
        $user_data['registration_status'] = $data['registration_status'];
        $this->tableGateway->insert($user_data);
        $id = $this->tableGateway->lastInsertValue;
        return $id;
    }
    
    public function getStudentStatus($studentId){
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('ss' => 'student_status'))
                ->columns(array('registration_status','verbal_reg_status','quant_status'));               
        $select->where(array('student_id' => $studentId));
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
        
    }
}
