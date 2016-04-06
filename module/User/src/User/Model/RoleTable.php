<?php

namespace User\Model;

use Zend\Db\TableGateway\TableGateway;
use ZF2AuthAcl\Utility\UserPassword;
use ZF2AuthAcl\Model\UserRole;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
use Zend\Session\Container;

class RoleTable {

    protected $tableGateway;
    protected $adapter;
    public $table = 'role';

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }

    public function getUserRole($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('rid' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

//    public function getUserDetails($id) {
//        $sql = new Sql($this->tableGateway->getAdapter());
//        $select = $sql->select();
//        $select->from(array('r' => 'role'))
//                ->columns(array('rid', 'role_name', 'role_code', 'status', 'parent_role_code'))
//                ->where(array('rid' => $id));
//        $statement = $sql->prepareStatementForSqlObject($select);
//        $resultset = $this->resultSetPrototype->initialize($statement->execute());
//                
//        return $resultset;
//    }
//    public function fetchAll1() {
//
//        $sql = new Sql($this->tableGateway->getAdapter());
//        $select = $sql->select();
//        $select->from(array('r' => 'role'));
//        $select->columns(array('rid', 'role_name', 'role_code', 'status'));
//
//
//
//        $statement = $sql->prepareStatementForSqlObject($select);
//        $resultset = $this->resultSetPrototype->initialize($statement->execute())
//                ->toArray();
//        return $resultset;
//    }

    public function fetchAll($paginated = false, $order_by = 'rid', $order = 'ASC', $searchText = NULL) {
        // create a new Select object for the table unit
        // $select = new Select('units');
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('r' => 'role'))
                ->columns(array('rid', 'role_name', 'role_code', 'status', 'parent_role_code'))
                ->order($order_by . ' ' . $order)
                ->group(array('r.rid', 'r.role_name', 'r.role_code', 'r.status', 'r.parent_role_code'));
        if ($order_by && $order) {
            $select->order($order_by . ' ' . $order);
        }
        try {
            if (isset($searchText)) {
                $searchCount = strlen($searchText);
                if ($searchCount > 100) {
                    throw new SearchTextLimit('Search Keyword length can not be more than 100');
                }
                $select->where->like('role_name', "%" . $searchText . "%")
                ->or->like('role_code', "%" . $searchText . "%")
                ->or->like('status', "%" . $searchText . "%");
            }
            if ($paginated) {
                // create a new result set based on the Album entity
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Role());
                // create a new pagination adapter object
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
            $resultSet = $this->tableGateway->select();
            return $resultSet;
        } catch (SearchTextLimit $e) {
            throw new SearchTextLimit('Search Keyword length can not be more than 100');
        }
    }

    public function fetchSystemRoles() {
        $session = new Container('User');
        $roleCode = $session->offsetGet('roleCode');
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('r' => 'role'))
                ->columns(array('rid', 'role_name', 'role_code', 'status'))
                ->where(array('parent_role_code' => '0'))
        ->where->notEqualTo('role_code', $roleCode);
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        $result = array();
        foreach ($resultset as $sys_role) {
            $result[$sys_role['rid']] = $sys_role['role_name'];
        }

        return $result;
    }

    public function saveUserRole($role) {
//        echo "<pre>";
//        print_r($role);
//        die;
        $data = array(
            'role_name' => strtoupper($role->role_name),
            'role_code' => $role->role_code,
            'parent_role_code' => $role->parent_role,
            'status' => 'Active',
        );

        $id = (int) $role->rid;
        if ($id == 0) {
            if ($this->tableGateway->insert($data)) {
                $userRoleId = $this->tableGateway->getLastInsertValue();
            }
        } else {
            if ($this->getUserRole($id)) {
                $userRoleId = $this->tableGateway->update($data, array('rid' => $id));
                $userRoleId = $id;
            } else {
                throw new \Exception('Unit Role Id does not exist');
            }
        }
        return $userRoleId;
    }

}
