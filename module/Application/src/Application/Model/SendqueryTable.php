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

    public function fetchAll($paginated = false, $order_by = 'id', $order = 'ASC', $searchText = NULL) {
        if ($order_by == 'id' || $order_by == 'name' || $order_by = 'mobile' || $order_by = 'email' || $order_by = 'status') {
            $order_by = 'sq.' . $order_by;
        }
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select()->from(array('sq' => 'send_query'), array('id', 'name', 'email', 'message', 'status'));
        $select->order($order_by . ' ' . $order);
        if (isset($searchText) && trim($searchText) != '') {
            $select->where->like('sq.name', "%" . $searchText . "%")
            ->or->like('sq.mobile', "%" . $searchText . "%")
            ->or->like('sq.email', "%" . $searchText . "%")
            ->or->like('sq.id', "%" . $searchText . "%");
        }
        if ($paginated) {
            $resultSetPrototype = new ResultSet();
            $paginatorAdapter = new DbSelect(
                    // our configured select object
                    $select,
                    // the adapter to run it against
                    $this->tableGateway->getAdapter(),
                    // the result set to hydrate
                    $resultSetPrototype
            );
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }

    public function getQueryList() {
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

    public function saveQuery($query) {
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
    
    public function updateQueryStatus($id){
        $query_data['status'] =1 ;
        $this->tableGateway->update($query_data, array('id' => $id));
        return $query_data;
    }

}
