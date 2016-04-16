<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Subject\Controller\Category' => 'Subject\Controller\CategoryController',
            'Subject\Controller\Subject' => 'Subject\Controller\SubjectController',),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'category' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/category[/:action][/:id][/page/:page][/msg/:msg]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Subject\Controller\Category',
                        'action' => 'index',
                    ),
                ),
            ),
            'subject' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/subject[/:action][/:id][/page/:page][/msg/:msg]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Subject\Controller\Subject',
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
