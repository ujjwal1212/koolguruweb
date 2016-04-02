<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Blog\Controller\Index' => 'Blog\Controller\IndexController',),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'blog' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/blog[/:action][/:id][/page/:page][/msg/:msg]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            
            'checklogin' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/checklogin[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'checklogin',
                    ),
                ),
            ),
            
            'getblog' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/getblog[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'getblog',
                    ),
                ),
            ),
            
            'add' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/add[/:action][/:id][/page/:page][/msg/:msg]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'add',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
    ),
);
