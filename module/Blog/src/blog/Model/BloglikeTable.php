<?php

namespace Blog\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
Use Zend\Db\Sql\Expression;
use Zend\Session\Container;

class BloglikeTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }
    
    public function updateStatus($data) {  
        $resultset = array();
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('bp' => 'blog_likes'));                
        $select->columns(array('id','status'));
        $select->where(array('bp.blog_id'=>$data['blog_id']));
        $select->where(array('bp.user_id'=>$data['user_id']));
        $select->where (array('bp.is_student'=>$data['is_student']));
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        
        if(empty($resultset)){
            $this->tableGateway->insert($data);
        }else{
            unset($data['created_at']);
            $this->tableGateway->update($data, array('id' => $resultset[0]['id']));
        }        
    }
    
    
    public function getBlogStatus($data){
        $resultset = array();
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('bp' => 'blog_likes'));
        $select->columns(array('id','status'));
        $select->where(array('bp.blog_id'=>$data['blog_id']));
        $select->where(array('bp.user_id'=>$data['user_id']));
        $select->where (array('bp.is_student'=>$data['is_student']));
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }
    
   
}
