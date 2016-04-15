<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Quiz\Controller\Quiz' => 'Quiz\Controller\QuizController',),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'quiz' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/quiz[/:action][/:id][/page/:page][/msg/:msg]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Quiz\Controller\Quiz',
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
