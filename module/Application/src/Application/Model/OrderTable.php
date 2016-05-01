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

    public function getDemoChapter() {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('c' => 'chapters'))
                ->join(array('sc' => 'subject_chapter_map'), 'sc.chapter_id=c.id')
                ->join(array('s' => 'subjects'), 's.id = sc.subject_id', array('chapter_id' => 'id', 'subject_title' => 'title'), 'left');
        $select->columns(array('chapter_id' => 'id', 'demo_chapter_title' => 'title', 'chapter_content' => 'content'));
        $select->where(array('c.isdemo' => 1));
        $select->where(array('c.status' => 1));
        $select->where(array('s.status' => 1));

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }

}
