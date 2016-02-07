<?php

/*
 * Breadcrumb Navigation
 */
return array(
    'service_manager' => array(
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory', // <-- add this
        ),
    ),
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Manage User',
                'route' => 'user',
                'pages' => array(
                    array(
                        'label' => ' Create User',
                        'route' => 'user',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit User',
                        'route' => 'user',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View User',
                        'route' => 'user',
                        'action' => 'view',
                    ),
                    array(
                        'label' => 'Change Password',
                        'route' => 'user',
                        'action' => 'resetpassword',
                    ),
//                    array(
//                        'label' => 'View Profile',
//                        'route' => 'user',
//                        'action' => 'viewprofile',
//                    ),
//                    array(
//                        'label' => 'Edit Profile',
//                        'route' => 'user',
//                        'action' => 'editprofile',
//                    ),
//                    array(
//                        'label' => 'View Profile',
//                        'route' => 'user',
//                        'action' => 'viewcenterprofile',
//                    ),
//                    array(
//                        'label' => 'Edit Profile',
//                        'route' => 'user',
//                        'action' => 'editcenterprofile',
//                    ),
                ),
            ),
            array(
                'label' => 'Manage Role',
                'route' => 'userrole',
                'pages' => array(
                    array(
                        'label' => ' Create Role',
                        'route' => 'userrole',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit Role',
                        'route' => 'userrole',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View Role',
                        'route' => 'userrole',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'Support',
                'route' => 'support',
//                'pages' => array(
//                    array(
//                        'label' => '',
//                        'route' => 'support',
//                        'action' => 'index',
//                    ),
//                ),
            ),
            array(
                'label' => 'Manage Level',
                'route' => 'level',
                'pages' => array(
                    array(
                        'label' => ' Create Level',
                        'route' => 'level',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit Level',
                        'route' => 'level',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View Level',
                        'route' => 'level',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'Manage Questions',
                'route' => 'question',
                'pages' => array(
                    array(
                        'label' => ' Create Question',
                        'route' => 'question',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit Question',
                        'route' => 'question',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View Question',
                        'route' => 'question',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'Manage Admin Activities',
                'route' => 'admin',
                'pages' => array(
                    array(
                        'label' => ' Manage Contact Queries',
                        'route' => 'admin',
                        'action' => 'contactquery',
                    ),
                    array(
                        'label' => 'Manage Student Registration',
                        'route' => 'admin',
                        'action' => 'student register',
                    ),
                ),
            ),
            array(
                'label' => 'Manage Category',
                'route' => 'category',
                'pages' => array(
                    array(
                        'label' => ' Create Category',
                        'route' => 'category',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit Category',
                        'route' => 'category',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View Category',
                        'route' => 'category',
                        'action' => 'view',
                    ),
                ),
            ),
        ),
    ),
);
