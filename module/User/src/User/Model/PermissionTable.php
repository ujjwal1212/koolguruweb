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

class PermissionTable {

    protected $tableGateway;
    protected $adapter;
    public $table = 'permission';

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }

    /**
     * fetch read only permission 
     * @param type $resourceId
     * @return type
     */
    public function getReadResourcePermission($resourceId, $parenRoleId) {

        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('p' => 'permission'));
        $select->columns(array('id', 'permission_name', 'resource_id'))
                ->join(array('rp' => 'role_permission'), 'rp.permission_id = p.id', array(), 'left')
        ->where->NEST->equalTo('p.resource_id', $resourceId)
        ->AND->equalTo('rp.role_id', $parenRoleId)
        ->AND->NEST->like('p.permission_name', "view%")
        ->OR->like('p.permission_name', "index");

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }

    public function getFullResourcePermission($resourceId, $parenRoleId) {

        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('p' => 'permission'));
        $select->columns(array('id', 'permission_name', 'resource_id'))
                ->join(array('rp' => 'role_permission'), 'rp.permission_id = p.id', array(), 'left')
        ->where->NEST->equalTo('p.resource_id', $resourceId)
        ->AND->equalTo('rp.role_id', $parenRoleId);

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }

}
