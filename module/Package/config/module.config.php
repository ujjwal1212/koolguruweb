<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Package\Controller\Package' => 'Package\Controller\PackageController',),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'package' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/package[/:action][/:id][/page/:page][/msg/:msg]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Package\Controller\Package',
                        'action' => 'index',
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
