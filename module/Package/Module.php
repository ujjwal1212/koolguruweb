<?php

namespace Package;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Package\Model\Package;
use Package\Model\PackageTable;
use Package\Model\coursePackage;
use Package\Model\coursePackageTable;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
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
                'Package\Model\PackageTable' => function($sm) {
                    $tableGateway = $sm->get('PackageTableGateway');
                    $table = new PackageTable($tableGateway);
                    return $table;
                },
                'PackageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Package());
                    return new TableGateway('package', $dbAdapter, null, $resultSetPrototype);
                },
                'Package\Model\coursePackageTable' => function($sm) {
                    $tableGateway = $sm->get('coursePackageTableGateway');
                    $table = new coursePackageTable($tableGateway);
                    return $table;
                },
                'coursePackageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new coursePackage());
                    return new TableGateway('package_course_map', $dbAdapter, null, $resultSetPrototype);
                },
            )
        );
    }

}
