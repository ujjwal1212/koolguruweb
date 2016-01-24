<?php

namespace Student\Model;

use Zend\Db\TableGateway\TableGateway;

use Student\Model\CarrierQuestion;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
use Zend\Session\Container;

class CarrierQuestionTable {

    protected $tableGateway;
    protected $adapter;
    public $table = 'carrier_oriented_question';

    public function __construct(TableGateway $tableGateway) {       
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }
    
    public function getCarrierQuestions($question_id=''){
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('cq' => 'carrier_oriented_question'))
                ->join(array('cqo'=>'carrier_oriented_question_options'),'cq.id = cqo.question_id',array('option_title'=>'title','option_id'=>'id'),'left');
        $select->columns(array('id', 'title', 'note'));
        if($question_id!=''){
            $select->where(array('cq.id'=>$question_id));
        }
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        
        $questions = array();       
        foreach($resultset as $key=>$dat){
            if(empty($quetemp)){
                $quetemp[] = $dat['id'];
                $questions[$dat['id']]['title'] =  $dat['title'];
                $questions[$dat['id']]['note'] =  $dat['note'];
            }else{
                if(!in_array($dat['id'], $quetemp)){                    
                    $quetemp[] = $dat['id'];
                    $questions[$dat['id']]['title'] =  $dat['title'];
                    $questions[$dat['id']]['note'] =  $dat['note'];
                }
            }
            $questions[$dat['id']]['options'][$dat['option_id']] = $dat['option_title'];
        }
        return $questions;
        
        
        
        
        
        
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select()->from(array('r' => 'srva'), array('id', 'degree_name'));
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        $result = array();        
        foreach ($resultset as $degree) {
            $result[$degree['id']] = $degree['degree_name'];
        }
        return $result;
    }
    

}
