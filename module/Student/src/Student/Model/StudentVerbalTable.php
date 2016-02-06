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

class StudentVerbalTable {

    protected $tableGateway;
    protected $adapter;
    public $table = 'student_reg_verbal_ability';

    public function __construct(TableGateway $tableGateway) {       
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }
    
    
    public function saveStudentVerbalDetail($studentDet){        
        $studentid = $studentDet['student_id'];
        unset($studentDet['student_id']);
        unset($studentDet['verbalsubmit']);
        unset($studentDet['marks_total_verbal']);
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
    
    
    
//    public function getStudentDet(){
//        $sql = new Sql($this->tableGateway->getAdapter());
//        $select = $sql->select()->from(array('r' => 'srva'), array('id', 'degree_name'));
//        $statement = $sql->prepareStatementForSqlObject($select);
//        $resultset = $this->resultSetPrototype->initialize($statement->execute())
//                ->toArray();
//        $result = array();        
//        foreach ($resultset as $degree) {
//            $result[$degree['id']] = $degree['degree_name'];
//        }
//        return $result;
//    }
    

}
