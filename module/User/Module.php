<?php

namespace User;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use User\Model\Role;
use User\Model\RoleTable;
use User\Model\RolePermissionTable;
use User\Model\PermissionTable;
use User\Model\UserRole;
use User\Model\UserRoleTable;

class Module {

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'User\Model\RoleTable' => function($sm) {
            $tableGateway = $sm->get('RoleTableGateway');
            $table = new RoleTable($tableGateway);
            return $table;
        },
                'RoleTableGateway' => function ($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Role());
            return new TableGateway('role', $dbAdapter, null, $resultSetPrototype);
        },
                'User\Model\RolePermissionTable' => function($sm) {
            $tableGateway = $sm->get('RolePermissionTableGateway');
            $table = new RolePermissionTable($tableGateway);
            return $table;
        },
                'RolePermissionTableGateway' => function ($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Role());
            return new TableGateway('role_permission', $dbAdapter, null, $resultSetPrototype);
        },
                'User\Model\PermissionTable' => function($sm) {
            $tableGateway = $sm->get('PermissionTableGateway');
            $table = new PermissionTable($tableGateway);
            return $table;
        },
                'PermissionTableGateway' => function ($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Role());
            return new TableGateway('permission', $dbAdapter, null, $resultSetPrototype);
        },
            ),
        );
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

}
