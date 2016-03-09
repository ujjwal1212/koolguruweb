<?php

namespace Student\Model;

use Zend\Db\TableGateway\TableGateway;

use Student\Model\CarrierAnswers;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
use Zend\Session\Container;

class CarrierAnswersTable {

    protected $tableGateway;
    protected $adapter;
    public $table = 'student_carrier_oriented_answers';

    public function __construct(TableGateway $tableGateway) {       
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }
    
    public function saveCarrierAnswers($ans){
        $data = array(            
            'student_id' => $ans['student_id'],
            'question_id' => $ans['question_id'],
            'answer' => $ans['answer'],
        );
        $this->tableGateway->insert($data);
        $id = $this->tableGateway->lastInsertValue;
        return $id;
    }
    
    public function getStudentAnwers($studentid,$questionid=''){        
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('scoa' => 'student_carrier_oriented_answers'));
        $select->columns(array('student_id', 'question_id', 'answer'));
        if($questionid!=''){
            $select->where(array('scoa.question_id'=>$questionid));
        }
        $select->where(array('scoa.student_id'=>$studentid));

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
        
    }
    
    public function getCarrierMsg($questionid,$optionid){        
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('scoa' => 'carrier_oriented_message'));
        $select->columns(array('message'));
        $select->where(array('scoa.question_id'=>$questionid));
        $select->where(array('scoa.option_id'=>intval($optionid)));

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
        
    }
}
