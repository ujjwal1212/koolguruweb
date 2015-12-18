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
                'label' => 'Manage Trainees',
                'route' => 'trainee',
                'pages' => array(
                    array(
                        'label' => 'Create Trainee',
                        'route' => 'trainee',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit Trainee',
                        'route' => 'trainee',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View Trainee',
                        'route' => 'trainee',
                        'action' => 'view',
                    ),
                    array(
                        'label' => 'View Portfolio',
                        'route' => 'trainee',
                        'action' => 'portfolio',
                    ),
                ),
            ),
            array(
                'label' => 'Manage Intake',
                'route' => 'intake',
                'pages' => array(
                    array(
                        'label' => 'Create Intake',
                        'route' => 'intake',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit Intake',
                        'route' => 'intake',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View Intake',
                        'route' => 'intake',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'Manage Cohort',
                'route' => 'cohort',
                'pages' => array(
                    array(
                        'label' => 'Add Cohort',
                        'route' => 'cohort',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit Cohort',
                        'route' => 'cohort',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View Cohort',
                        'route' => 'cohort',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'Manage Qualification',
                'route' => 'qualification',
                'pages' => array(
                    array(
                        'label' => ' Create Standard Qualification',
                        'route' => 'qualification',
                        'action' => 'add',
                    ),
                    array(
                        'label' => ' Create Custom Qualification',
                        'route' => 'qualification',
                        'action' => 'addcustom',
                    ),
                    array(
                        'label' => 'Edit Standard Qualification',
                        'route' => 'qualification',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'Edit Custom Qualification',
                        'route' => 'qualification',
                        'action' => 'editcustom',
                    ),
                    array(
                        'label' => 'View Standard Qualification',
                        'route' => 'qualification',
                        'action' => 'view',
                    ),
                    array(
                        'label' => 'View Custom Qualification',
                        'route' => 'qualification',
                        'action' => 'viewcustom',
                    ),
                ),
            ),
            array(
                'label' => 'Manage Sector',
                'route' => 'sector',
                'pages' => array(
                    array(
                        'label' => ' Create Sector',
                        'route' => 'sector',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit Sector',
                        'route' => 'sector',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View Sector',
                        'route' => 'sector',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'Manage Units',
                'route' => 'unit',
                'pages' => array(
                    array(
                        'label' => ' Create Unit',
                        'route' => 'unit',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit Unit',
                        'route' => 'unit',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View Unit',
                        'route' => 'unit',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'Manage Qualification Request',
                'route' => 'qualificationrequest',
                'pages' => array(
                    array(
                        'label' => ' Add Qualification Approval Request',
                        'route' => 'qualificationrequest',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'View Qualification Approval Request',
                        'route' => 'qualificationrequest',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'MANAGE RENEW QUALIFICATION APPROVAL',
                'route' => 'qualificationrenewal',
                'pages' => array(
                    array(
                        'label' => ' Renew Qualification Approval Request',
                        'route' => 'qualificationrenewal',
                        'action' => 'renew',
                    ),
                    array(
                        'label' => 'View Qualification Approval Request',
                        'route' => 'qualificationrenewal',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'Manage Center',
                'route' => 'center',
                'pages' => array(
                    array(
                        'label' => ' Create Center',
                        'route' => 'center',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit Center',
                        'route' => 'center',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View Center',
                        'route' => 'center',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'Manage Satellite Center',
                'route' => 'satellitecenter',
                'pages' => array(
                    array(
                        'label' => ' Create Satellite Center',
                        'route' => 'satellitecenter',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit Satellite Center',
                        'route' => 'satellitecenter',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View Satellite Center',
                        'route' => 'satellitecenter',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'Manage Center Group',
                'route' => 'centergroup',
                'pages' => array(
                    array(
                        'label' => ' Create CenterGroup',
                        'route' => 'centergroup',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit CenterGroup',
                        'route' => 'centergroup',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View CenterGroup',
                        'route' => 'centergroup',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'QUALIFICATION APPROVAL/RENEWAL REQUEST',
                'route' => 'centerqualificationassociation',
                'pages' => array(
                    array(
                        'label' => 'View QUALIFICATION APPROVAL/RENEWAL REQUEST',
                        'route' => 'centerqualificationassociation',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'Manage Assessment Booking ',
                'route' => 'assessment',
                'pages' => array(
                    array(
                        'label' => ' Add Assessment Booking',
                        'route' => 'assessment',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit Assessment Booking',
                        'route' => 'assessment',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View Assessment Booking',
                        'route' => 'assessment',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'Manage External Verifier',
                'route' => 'externalverifier',
                'pages' => array(
                    array(
                        'label' => 'Create External Verifier',
                        'route' => 'externalverifier',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit External Verifier',
                        'route' => 'externalverifier',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View External Verifier',
                        'route' => 'externalverifier',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'Manage External Verifier Visit',
                'route' => 'externalverifierbooking',
                'pages' => array(
                    array(
                        'label' => 'Create External Verifier Visit',
                        'route' => 'externalverifierbooking',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit External Verifier Visit',
                        'route' => 'externalverifierbooking',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View External Verifier Visit',
                        'route' => 'externalverifierbooking',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'Manage Grading Rules',
                'route' => 'gradingrules',
                'pages' => array(
                    array(
                        'label' => 'Manage Grading Rules',
                        'route' => 'externalverifierbooking',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'Manage Grading Rules',
                        'route' => 'externalverifierbooking',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'Manage Malpractice',
                'route' => 'malpractice',
                'pages' => array(
                    array(
                        'label' => ' Create Malpractice',
                        'route' => 'malpractice',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit Malpractice',
                        'route' => 'malpractice',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View Malpractice',
                        'route' => 'malpractice',
                        'action' => 'view',
                    ),
                ),
            ),
            array(
                'label' => 'Manage Appeals',
                'route' => 'appeal',
                'pages' => array(
                    array(
                        'label' => ' Create Appeal',
                        'route' => 'appeal',
                        'action' => 'add',
                    ),
                    array(
                        'label' => ' ADD APPEAL FOR CUSTOM QUALIFICATION',
                        'route' => 'appeal',
                        'action' => 'addcustom',
                    ),
                    array(
                        'label' => 'Edit Appeal',
                        'route' => 'appeal',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'Edit Appeal FOR CUSTOM QUALIFICATION',
                        'route' => 'appeal',
                        'action' => 'editcustom',
                    ),
                    array(
                        'label' => 'View Appeal',
                        'route' => 'appeal',
                        'action' => 'view',
                    ),
                    array(
                        'label' => 'View Appeal for Custom Qualification',
                        'route' => 'appeal',
                        'action' => 'viewcustom',
                    ),
                ),
            ),
            array(
                'label' => 'MANAGE ASSESSMENT BOOKING CRITERIA',
                'route' => 'assessmentbookingcriteria',
            ),
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
                'label' => 'Result',
                'route' => 'result',
                'pages' => array(
//                    array(
//                        'label' => 'GRADE MODELING',
//                        'route' => 'result',
//                        'action' => 'gradeModeling',
//                    ),
//                    array(
//                        'label' => 'Publish Provisional Result',
//                        'route' => 'result',
//                        'action' => 'index',
//                    ),
                    array(
                        'label' => 'Edit External Verifier',
                        'route' => 'externalverifier',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'View Provisional Result',
                        'route' => 'result',
                        'action' => 'view',
                    ),
                    array(
                        'label' => 'View Final Result',
                        'route' => 'result',
                        'action' => 'viewFinalResult',
                    ),
                    array(
                        'label' => 'Publish Final Result',
                        'route' => 'result',
                        'action' => 'final',
                    ),
                ),
            ),
            array(
                'label' => 'Messages',
                'route' => 'messages',
                'pages' => array(
                    array(
                        'label' => 'Inbox',
                        'route' => 'messages',
                        'action' => 'index',
                    ),
                    array(
                        'label' => 'Sent Messages',
                        'route' => 'messages',
                        'action' => 'sent',
                    ),
                    array(
                        'label' => 'Trash',
                        'route' => 'messages',
                        'action' => 'trash',
                    ),
                ),
            ),
            array(
                'label' => 'Announcements',
                'route' => 'announcement',
                'pages' => array(
//                    array(
//                        'label' => 'Announcements Listing',
//                        'route' => 'announcement',
//                        'action' => 'index',
//                    ),
                    array(
                        'label' => 'Create Announcement',
                        'route' => 'announcement',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit Announcement',
                        'route' => 'announcement',
                        'action' => 'edit',
                    ),
                ),
            ),
            array(
                'label' => 'Calendar',
                'route' => 'calendar',
                'pages' => array(
//                    array(
//                        'label' => '',
//                        'route' => 'calendar',
//                        'action' => 'index',
//                    ),
                ),
            ),
            array(
                'label' => 'TRAINEE REPORT',
                'route' => 'traineereport',
            ),
            array(
                'label' => 'EXAM & RESULT REPORT',
                'route' => 'examresult',
            ),
            array(
                'label' => 'External Verifier Report',
                'route' => 'evreport',
            ),
            array(
                'label' => 'Center Report',
                'route' => 'centerreport',
            ),
            array(
                'label' => 'Malpractice Report',
                'route' => 'malpracticereport',
            ),
            array(
                'label' => 'Reports',
                'route' => 'qualificationreport',
                'pages' => array(
                    array(
                        'label' => ' QUALIFICATION REPORT',
                        'route' => 'qualificationreport',
                        'action' => 'index',
                    ),
                ),
            ),
            array(
                'label' => 'Custom Result',
                'route' => 'customresult',
                'pages' => array(
                    array(
                        'label' => ' Generate Result',
                        'route' => 'customresult',
                        'action' => 'index',
                    ),
                    array(
                        'label' => ' Publish Result',
                        'route' => 'customresult',
                        'action' => 'publishfinalresult',
                    ),
                    array(
                        'label' => ' View Result',
                        'route' => 'customresult',
                        'action' => 'viewfinalresult',
                    ),
                ),
            ),
        ),
    ),
);
