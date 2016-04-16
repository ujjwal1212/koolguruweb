<?php

return array(
    'controllers' => array(
        'invokables' => array(            
            'Chapter\Controller\Chapter' => 'Chapter\Controller\ChapterController',),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(            
            'chapter' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/chapter[/:action][/:id][/page/:page][/msg/:msg]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Chapter\Controller\Chapter',
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
