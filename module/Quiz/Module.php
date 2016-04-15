<?php

namespace Quiz;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Quiz\Model\Quiz;
use Quiz\Model\QuizTable;
use Quiz\Model\Quizlevel;
use Quiz\Model\QuizlevelTable;

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
                'Quiz\Model\QuizTable' => function($sm) {
                    $tableGateway = $sm->get('QuizTableGateway');
                    $table = new QuizTable($tableGateway);
                    return $table;
                },
                'QuizTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Quiz());
                    return new TableGateway('quiz', $dbAdapter, null, $resultSetPrototype);
                },
                'Quiz\Model\QuizlevelTable' => function($sm) {
                    $tableGateway = $sm->get('QuizlevelTableGateway');
                    $table = new QuizlevelTable($tableGateway);
                    return $table;
                },
                'QuizlevelTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Quizlevel());
                    return new TableGateway('quiz_level', $dbAdapter, null, $resultSetPrototype);
                },
                
            )
        );
    }

}
