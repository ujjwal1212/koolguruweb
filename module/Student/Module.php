<?php
namespace Student;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Student\Model\Degree;
use Student\Model\DegreeTable;
use Student\Model\State;
use Student\Model\StateTable;
use Student\Model\Student;
use Student\Model\StudentTable;

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
                'Student\Model\DegreeTable' => function($sm) {
                    $tableGateway = $sm->get('DegreeTableGateway');
                    $table = new DegreeTable($tableGateway);
                    return $table;
                },
                'DegreeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Degree());
                    return new TableGateway('degree', $dbAdapter, null, $resultSetPrototype);
                },
                'Student\Model\StateTable' => function($sm) {                    
                    $tableGateway = $sm->get('StateTableGateway');
                    $table = new StateTable($tableGateway);
                    return $table;
                },
                'StateTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new State());
                    return new TableGateway('state', $dbAdapter, null, $resultSetPrototype);
                },
                'Student\Model\StudentTable' => function($sm) {                    
                    $tableGateway = $sm->get('StudentTableGateway');
                    $table = new StudentTable($tableGateway);
                    return $table;
                },
                'StudentTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Student());
                    return new TableGateway('student', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

}
