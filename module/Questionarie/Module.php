<?php

namespace Questionarie;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Questionarie\Model\Level;
use Questionarie\Model\LevelTable;
use Questionarie\Model\Question;
use Questionarie\Model\QuestionTable;
use Questionarie\Model\QuestionOption;
use Questionarie\Model\QuestionOptionTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

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
                'Questionarie\Model\LevelTable' => function($sm) {
                    $tableGateway = $sm->get('LevelTableGateway');
                    $table = new LevelTable($tableGateway);
                    return $table;
                },
                'LevelTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Level());
                    return new TableGateway('level', $dbAdapter, null, $resultSetPrototype);
                },
                'Questionarie\Model\QuestionTable' => function($sm) {
                    $tableGateway = $sm->get('QuestionTableGateway');
                    $table = new QuestionTable($tableGateway);
                    return $table;
                },
                'QuestionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Question());
                    return new TableGateway('questions', $dbAdapter, null, $resultSetPrototype);
                },
                'Questionarie\Model\QuestionOptionsTable' => function($sm) {
                    $tableGateway = $sm->get('QuestionOptionsTableGateway');
                    $table = new QuestionOptionTable($tableGateway);
                    return $table;
                },
                'QuestionOptionsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new QuestionOption());
                    return new TableGateway('questions_options', $dbAdapter, null, $resultSetPrototype);
                },
            )
        );
    }

}
