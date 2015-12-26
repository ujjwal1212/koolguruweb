<?php

namespace Questionarie\Model;

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

class LevelTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }

    /**
     * Function to Fetch listing for Manage Centers Page
     * @param type $paginated
     * @param type $searchText
     * @return \Zend\Paginator\Paginator
     */
    public function fetchAll($paginated = false, $order_by = 'id', $order = 'ASC', $searchText = NULL) {

        if ($order_by == 'id' || $order_by == 'name') {
            $order_by = 'l.' . $order_by;
        }

        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('l' => 'level'));
        $select->columns(array('id', 'name', 'description'));
        $select->order($order_by . ' ' . $order);
        if (isset($searchText) && trim($searchText) != '') {
            $select->where->like('l.name', "%" . $searchText . "%")
            ->or->like('l.id', "%" . $searchText . "%");
        }
//        $statement = $sql->prepareStatementForSqlObject($select);
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


    /**
     * Function to Save Center Record to Database.
     * @throws \Exception
     */
    public function saveLevel(Level $level) {
        $data = array(
            'name' => trim($level->name),
            'description' => $level->description,
        );

        $id = (int) $level->id;
        if ($id == 0) {
            $data['created_date'] = time();
            $data['created_by'] = $level->created_by;
            $data['updated_date'] = time();
            $data['updated_by'] = $level->created_by;
            if ($this->tableGateway->insert($data)) {
                $levelId = $this->tableGateway->getLastInsertValue();
            }
        } else {
            if ($this->getLevel($id)) {
                $data['updated_date'] = time();
                $data['updated_by'] = $level->updated_by;
                $levelId = $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Level id does not exist');
            }
        }
        return $levelId;
    }
    
    public function getLevel($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
}
