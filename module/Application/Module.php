<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Application\Model\Sendquery;
use Application\Model\SendqueryTable;
use Application\Model\Chapter;
use Application\Model\ChapterTable;
use Application\Model\Subject;
use Application\Model\SubjectTable;

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
                'Application\Model\SendqueryTable' => function($sm) {
                    $tableGateway = $sm->get('SendqueryTableGateway');
                    $table = new SendqueryTable($tableGateway);
                    return $table;
                },
                'SendqueryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Sendquery());
                    return new TableGateway('send_query', $dbAdapter, null, $resultSetPrototype);
                },
                        
                'Application\Model\SubjectTable' => function($sm) {
                    $tableGateway = $sm->get('SubjectTableGateway');
                    $table = new SubjectTable($tableGateway);
                    return $table;
                },
                'SubjectTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Subject());
                    return new TableGateway('subjects', $dbAdapter, null, $resultSetPrototype);
                },
                        
                'Application\Model\ChapterTable' => function($sm) {
                    $tableGateway = $sm->get('ChapterTableGateway');
                    $table = new ChapterTable($tableGateway);
                    return $table;
                },
                'ChapterTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Chapter());
                    return new TableGateway('chapters', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
