<?php

namespace Chapter;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Chapter\Model\Chapter;
use Chapter\Model\ChapterTable;
use Chapter\Model\Subjectchapter;
use Chapter\Model\SubjectchapterTable;


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
                'Chapter\Model\ChapterTable' => function($sm) {
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
                'Chapter\Model\SubjectchapterTable' => function($sm) {
                    $tableGateway = $sm->get('SubjectchapterTableGateway');
                    $table = new SubjectchapterTable($tableGateway);
                    return $table;
                },
                'SubjectchapterTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Subjectchapter());
                    return new TableGateway('subject_chapter_map', $dbAdapter, null, $resultSetPrototype);
                },
                
            )
        );
    }

}
