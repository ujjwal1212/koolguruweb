<?php

namespace Student\Model;

use Zend\Db\TableGateway\TableGateway;

use Student\Model\Degree;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
use Zend\Session\Container;

class DegreeTable {

    protected $tableGateway;
    protected $adapter;
    public $table = 'degree';

    public function __construct(TableGateway $tableGateway) {       
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }

    public function getDegree($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function fetchAll() {

        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('d' => 'degree'));
        $select->columns(array('id', 'degree_name', 'status'));
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }

    public function getDegreeList(){
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select()->from(array('r' => 'degree'), array('id', 'degree_name'));
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
