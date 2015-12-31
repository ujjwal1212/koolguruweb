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

class RolePermissionTable {

    protected $tableGateway;
    protected $adapter;
    public $table = 'role_permission';

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

    public function getExistingRolePermission($roleId, $permissionId) {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('rp' => 'role_permission'));
        $select->columns(array('id'));
        $select->where->NEST->equalTo('rp.role_id', $roleId)
        ->AND->equalTo('rp.permission_id', $permissionId);

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        foreach ($resultset as $res) {
            $resultset = $res;
        }
        return $resultset;
    }

    public function getRoleResource($roleId) {

        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('rp' => 'role_permission'));
        $select->columns(array('id', 'role_id', 'permission_id'))
                ->join(array('p' => 'permission'), 'p.id = rp.permission_id', array('resource_id'), 'left')
                ->join(array('r' => 'resource'), 'r.id = p.resource_id', array('resource_name'), 'left')
                ->where(array('rp.role_id' => $roleId));
        $select->group(array('r.id', 'r.resource_name', 'p.resource_id', 'rp.id', 'rp.role_id', 'rp.permission_id'));



        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        $rersourceArr = array();
        $resourceId = array();
        foreach ($resultset as $resource) {
            if (!in_array($resource['resource_id'], $resourceId)) {
                $resourceId[] = $resource['resource_id'];
                $rersourceArr[] = $resource['resource_name'] . '*' . $resource['resource_id'];
            }
        }
        return $rersourceArr;
    }

    public function getRoleResourcePermission($roleId) {

        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('rp' => 'role_permission'));
        $select->columns(array('id', 'role_id', 'permission_id'))
                ->join(array('p' => 'permission'), 'p.id = rp.permission_id', array('resource_id', 'permission_name'), 'left')
                ->join(array('r' => 'resource'), 'r.id = p.resource_id', array('resource_name'), 'left')
                ->where(array('rp.role_id' => $roleId));
        $select->group(array('r.id', 'r.resource_name', 'p.resource_id', 'rp.id', 'rp.role_id', 'rp.permission_id', 'p.permission_name'));



        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }

    public function saveRolePermission($roleId, $permissionId) {
        $data = array(
            'role_id' => $roleId,
            'permission_id' => $permissionId
        );
        $rolePermissionId = 0;

        if (!$this->getExistingRolePermission($roleId, $permissionId)) {
            if ($this->tableGateway->insert($data)) {
                $rolePermissionId = $this->tableGateway->getLastInsertValue();
            }
        }
//        else {
//            if ($this->getExistingRolePermission($roleId, $permissionId)) {
//                $sub = $sql->delete('role_permission');
//                $sub->where->equalTo('role_id', $roleId);
//                echo $sql->getSqlStringForSqlObject($sub);
//                die;
//                $sql->prepareStatementForSqlObject($sub)->execute(); // delete all the entries while editing role
//                // after deleting insert again to the rol_permission table
//                if ($this->tableGateway->insert($data)) {
//                    $rolePermissionId = $this->tableGateway->getLastInsertValue();
//                }
////                unset($data);
////                $data = array();
//                //$userRoleId = $id;
//            }
////            echo "<pre>";
////            print_r($existingRoleId[0]);
////            die;
////            $sub = $sql->delete('role_permission');
////            $sub->where->equalTo('id', $existingRoleId[0]['id']);
////            $sql->prepareStatementForSqlObject($sub)->execute();
////            if ($this->tableGateway->insert($data)) {
////                $rolePermissionId = $this->tableGateway->getLastInsertValue();
////            }
//        }
        return $rolePermissionId;
    }

// delete each permission for the role while updation role
    public function deleteRolePermission($roleId) {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->delete('role_permission');
        $select->where->equalTo('role_id', $roleId);
        $sql->prepareStatementForSqlObject($select)->execute();
    }

}
