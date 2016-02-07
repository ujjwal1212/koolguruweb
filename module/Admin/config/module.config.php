<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Admin' => 'Admin\Controller\AdminController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin[/:action][/:id]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Admin',
                        'action' => 'index',
                    ),
                ),
            ),
            
            'addcarrierpath' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/addcarrierpath[/:action][/:id][/page/:page][/msg/:msg]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Admin',
                        'action' => 'addcarrierpath',
                    ),
                ),
            ),
            'carrierpath' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/carrierpath[/:action][/:id][/page/:page][/msg/:msg]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Admin',
                        'action' => 'carrierpath',
                    ),
                ),
            ),
        ),
    ),    
    'view_manager' => array(
        'template_path_stack' => array(
            'admin' => __DIR__ . '/../view',
        ),
    ),
);
