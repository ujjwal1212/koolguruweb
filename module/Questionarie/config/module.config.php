<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Questionarie\Controller\Level' => 'Questionarie\Controller\LevelController',
            'Questionarie\Controller\Question' => 'Questionarie\Controller\QuestionController',),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'level' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/level[/:action][/:id][/page/:page][/msg/:msg]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Questionarie\Controller\Level',
                        'action' => 'index',
                    ),
                ),
            ),
            'question' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/question[/:action][/:id][/page/:page][/msg/:msg]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Questionarie\Controller\Question',
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
