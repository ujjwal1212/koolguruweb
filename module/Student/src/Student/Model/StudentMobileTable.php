<?php

namespace Student\Model;

use Zend\Db\TableGateway\TableGateway;

use Student\Model\StudentMobile;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
use Zend\Session\Container;

class StudentMobileTable {

    protected $tableGateway;
    protected $adapter;
    public $table = 'student_mobile';

    public function __construct(TableGateway $tableGateway) {       
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }

      
    public function fetchAll() {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select()->from(array('sm' => 'student_mobile'), array('id', 'mobile','isregistered','student_id','created'));
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }

    public function saveStudentMobile($student){        
        $user_data = array(            
            'mobile' => $student['mobile'],
            'isregistered' => $student['isregistered'],
            'student_id' => $student['student_id'],
            'created' => $student['created'],
        );
        $this->tableGateway->insert($user_data);
        $id = $this->tableGateway->lastInsertValue;
        return $id;
    }
    
    public function getIsMobileExist($mobile){
        $flg = false;
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('sm' => 'student_mobile'))
                ->columns(array('isregistered','mobile','student_id'));               
        $select->where(array('mobile' => $mobile));
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = array();
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        if(!empty($resultset)){
            $flg = true;
        }
        return $flg;
    }
    
    
    public function updateMobileStatus($mobile,$student){       
        $user_data = array();
        $user_data['isregistered'] = 1;
        $user_data['student_id'] = $student;
        $this->tableGateway->update($user_data, array('mobile' => $mobile));
        return $student;
    }

}
