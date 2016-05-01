<?php

namespace Application\Model;

use Application\Model\Order;
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
use Zend\Db\Adapter\Adapter;

class OrderTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }

    /**
     * Function to Fetch listing for Orders
     * @param type $paginated
     * @param type $searchText
     * @return \Zend\Paginator\Paginator
     */
    public function fetchAll($paginated = false, $order_by = 'id', $order = 'ASC', $searchText = NULL) {
        
    }

    /**
     * Function to Save Order Record to Database.
     * @throws \Exception
     */
    public function saveTransaction($package_id, $userId) {
        $data = array(
            'user_id' => $userId,
            'package_id' => $package_id,
            'status' => 0,
            'created_at' => time(),
            'created_by' => $userId,
        );
        $this->tableGateway->insert($data);

        $transactionId = $this->tableGateway->getLastInsertValue();
        return $transactionId;
    }

    public function updateTransaction($id, $userId) {
        $data = array(
            'user_id' => $userId,
            'package_id' => $id,
            'status' => 1,
        );
        $id = $this->tableGateway->update($data, array('user_id' => $userId, 'package_id' => $id));
        $id = $this->tableGateway->getLastInsertValue();
        return $id;
    }

    public function getOrderDetails($id, $userId) {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('o' => 'orders'));
        $select->columns(array('id', 'status'));
        $select->where(array('user_id' => $userId, 'package_id' => $id));
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }

    public function saveSubscription($id, $userId, $id, $package) {
        $session = new Container('User');
        $userId = $session->offsetGet('userId');
        $data = array(
            'order_id' => $id,
            'user_id' => $userId,
            'package_id' => $id,
            'is_expired' => 1,
            'start_date' => time(),
            'end_date' => $package[0]['end_date'],
            'created_at' => time(),
            'created_by' => $userId,
            'updated_at' => time(),
            'updated_by' => $userId
        );
        $sql = new Sql($this->tableGateway->getAdapter());
        $adapter = $this->tableGateway->getAdapter();
        $insert = $sql->insert('subscription');
        $insert->values($data);
        $selectString = $sql->getSqlStringForSqlObject($insert);
        $results = $adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $id = $adapter->getDriver()->getLastGeneratedValue();
        return $id;
    }

}
