<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

use Application\Model\Sendquery;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
use Zend\Session\Container;

class SendqueryTable {

    protected $tableGateway;
    protected $adapter;
    public $table = 'send_query';

    public function __construct(TableGateway $tableGateway) {       
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }

    public function getQueryList(){
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
    
    
    public function saveQuery($query){        
        $query_data = array(            
            'name' => $query['name'],
            'email' => $query['email'],
            'mobile' => $query['mobile'],
            'message' => $query['message'],             
            'createat' => time(),
        );
        $this->tableGateway->insert($query_data);
        $id = $this->tableGateway->lastInsertValue;
        return $id;
    }
    

}
