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
                    'route' => '/student[/:action][/:id][/:msg]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Student\Controller\Student',
                        'action' => 'index',
                    ),
                ),
            ),
            
            
            'studentregistration' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/studentregistration[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Student\Controller\Student',
                        'action' => 'studentregistration',
                    ),
                ),
            ),
            
            'save_mobile' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/save_mobile[/:action][/:mobile]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Student\Controller\Student',
                        'action' => 'savemobile',
                    ),
                ),
            ),
            
        ),
    ),    
    'view_manager' => array(
        'template_path_stack' => array(
            'student' => __DIR__ . '/../view',
        ),
    ),
);
