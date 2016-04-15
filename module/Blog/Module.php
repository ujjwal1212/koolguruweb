<?php

namespace Blog;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Blog\Model\Blog;
use Blog\Model\BlogTable;
use Blog\Model\Bloglike;
use Blog\Model\BloglikeTable;


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
                'Blog\Model\BlogTable' => function($sm) {
                    $tableGateway = $sm->get('BlogTableGateway');
                    $table = new BlogTable($tableGateway);
                    return $table;
                },
                'BlogTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Blog());
                    return new TableGateway('blog_post', $dbAdapter, null, $resultSetPrototype);
                },
                'Blog\Model\BloglikeTable' => function($sm) {
                    $tableGateway = $sm->get('BloglikeTableGateway');
                    $table = new BloglikeTable($tableGateway);
                    return $table;
                },
                'BloglikeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Bloglike());
                    return new TableGateway('blog_likes', $dbAdapter, null, $resultSetPrototype);
                },
            )
        );
    }

}
