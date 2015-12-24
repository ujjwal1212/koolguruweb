<?php

namespace Student\Model;

use Zend\Db\TableGateway\TableGateway;

use Student\Model\Student;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
use Zend\Session\Container;

class StudentTable {

    protected $tableGateway;
    protected $adapter;
    public $table = 'student';

    public function __construct(TableGateway $tableGateway) {        
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }

    public function getSudent($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
            return false;
        }
        return $row;
    }
    
    public function fetchAll() {

        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('d' => 'student'));
                
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }

    public function getStateList(){
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select()->from(array('r' => 'state'), array('id', 'state_name'));
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        $result = array();        
        foreach ($resultset as $state) {
            $result[$state['id']] = $state['state_name'];
        }
        return $result;
    }
    
    public function saveStudent($student){        
        $user_data = array(            
            'fname' => $student['fname'],
            'mname' => $student['mname'],
            'lname' => $student['lname'],
            'sex' => $student['sex'],
            'father_occupation' => $student['father_occupation'],
            'highest_degree' => $student['highest_degree'],
            'completion_year' => $student['completion_year'],
            'native_state' => $student['native_state'],
            'city' => $student['city'],
            'mobile' => $student['mobile'],   
            'email' => $student['email'],   
            'created' => time(),
            'updated' => time(),
            'status' => 0,
        );
        $this->tableGateway->insert($user_data);
        $student_id = $this->tableGateway->lastInsertValue;
        return $student_id;
    }
    
    public function getEnableTabList($studentId){
        $enabletablist = array();
        $studentDet = array();
        if($studentId == ''){
            $enabletablist = array(TRUE,0,0,0);
        }else{
            $studentDet = $this->getSudent($studentId);            
            if(empty($studentDet)){
                $enabletablist = array(TRUE,0,0,0);
            }else{
                $enabletablist = array(0,TRUE,0,0);
            }
        }
        return $enabletablist;
    }
    
    public function getEnableTabContentList($studentId){
        $enabletabContentlist = array();
        $studentDet = array();
        if($studentId == ''){
            $enabletabContentlist = array(TRUE,0,0,0);
        }else{
            $studentDet = $this->getSudent($studentId);            
            if(empty($studentDet)){
                $enabletabContentlist = array(TRUE,0,0,0);
            }else{
                $enabletabContentlist = array(TRUE,TRUE,0,0);
            }
        }
        return $enabletabContentlist;
    }
}
