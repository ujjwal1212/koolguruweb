<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'UserRest\Controller\UserRest' => 'UserRest\Controller\UserRestController',
            'UserRest\Controller\ClientRest' => 'UserRest\Controller\ClientRestController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'userrest' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/user-rest',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'UserRest\Controller',
                        'controller'    => 'UserRest',
                    ),
                ),
                 
                'may_terminate' => true,
                'child_routes' => array(
                    'client' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/client[/:action]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'ClientRest',
                                'action'     => 'index'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);