<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Admin\Model\Carrierpath;
use Admin\Model\CarrierpathTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Student\Model\Course;
use Student\Model\CourseTable;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
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
                'Admin\Model\CarrierpathTable' => function($sm) {
                    $tableGateway = $sm->get('CarrierpathTableGateway');
                    $table = new CarrierpathTable($tableGateway);
                    return $table;
                },
                'CarrierpathTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Carrierpath());
                    return new TableGateway('carrier_path', $dbAdapter, null, $resultSetPrototype);
                },
                'Student\Model\CourseTable' => function($sm) {
                    $tableGateway = $sm->get('CourseTableGateway');
                    $table = new CourseTable($tableGateway);
                    return $table;
                },
                'CourseTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Course());
                    return new TableGateway('course', $dbAdapter, null, $resultSetPrototype);
                },
            )
        );
    }
}
