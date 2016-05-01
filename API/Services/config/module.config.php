<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Services\Controller\User' => 'Services\Controller\UserController',),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'services' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/services[/:action][/:id][/page/:page][/msg/:msg]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Services\Controller\User',
                        'action' => 'userlogin',
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
