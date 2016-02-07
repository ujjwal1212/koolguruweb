<?php

namespace Package\Model;

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

class PackageTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }

    /**
     * Function to Fetch listing for Manage Subject Page
     * @param type $paginated
     * @param type $searchText
     * @return \Zend\Paginator\Paginator
     */
    public function fetchAll($paginated = false, $order_by = 'id', $order = 'ASC', $searchText = NULL) {

        if ($order_by == 'id' || $order_by == 'title' || $order_by == 'code') {
            $order_by = 'p.' . $order_by;
        }

        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('p' => 'package'));
        $select->columns(array('id', 'code', 'title'));
        $select->order($order_by . ' ' . $order);
        if (isset($searchText) && trim($searchText) != '') {
            $select->where->like('p.title', "%" . $searchText . "%")
            ->or->like('p.code', "%" . $searchText . "%")
            ->or->like('p.id', "%" . $searchText . "%");
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
     * Function to Save Package Record to Database.
     * @throws \Exception
     */
    public function savePackage(Package $package) {
        $data = array(
            'title' => trim($package->title),
            'code' => $package->code,
            'image_path' => $package->image_path,
            'status' => $package->status,
            'price' => $package->price,
            'duration' => $package->duration,
            'ff_classroom' => $package->ff_classroom,
            'relevant_for' => $package->relevant_for,
            'advantage' => $package->advantage,
            'description' => $package->description,
        );
        $id = (int) $package->id;
        if ($id == 0) {
            $data['created_at'] = time();
            $data['created_by'] = $package->created_by;
            $data['updated_at'] = time();
            $data['updated_by'] = $package->created_by;
            if ($this->tableGateway->insert($data)) {
                $packageId = $this->tableGateway->getLastInsertValue();
            }
        } else {
            if ($this->getPackage($id)) {
                $data['updated_at'] = time();
                $data['updated_by'] = $package->updated_by;
                $packageId = $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Package id does not exist');
            }
        }
        return $packageId;
    }

    public function getPackage($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getPackageDetails($id) {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('p' => 'package'));
        $select->columns(array('id', 'title', 'code', 'status', 'image_path','description','price','duration','relevant_for','advantage','ff_classroom'));
        $select->where(array('p.id' => $id));

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }

}
