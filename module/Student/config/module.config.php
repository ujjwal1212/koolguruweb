<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Student\Controller\Student' => 'Student\Controller\StudentController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'student' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/student[/:action][/:id]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Student\Controller\Student',
                        'action' => 'index',
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
