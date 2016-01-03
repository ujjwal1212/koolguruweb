<?php
namespace Student;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Student\Model\Degree;
use Student\Model\DegreeTable;
use Student\Model\State;
use Student\Model\StateTable;
use Student\Model\Student;
use Student\Model\StudentTable;
use Student\Model\StudentVerbal;
use Student\Model\StudentVerbalTable;
use Student\Model\StudentStatus;
use Student\Model\StudentStatusTable;
use Questionarie\Model\Question;
use Questionarie\Model\QuestionTable;
use Questionarie\Model\QuestionOption;
use Questionarie\Model\QuestionOptionTable;

class Module {

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'Student\Model\DegreeTable' => function($sm) {
                    $tableGateway = $sm->get('DegreeTableGateway');
                    $table = new DegreeTable($tableGateway);
                    return $table;
                },
                'DegreeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Degree());
                    return new TableGateway('degree', $dbAdapter, null, $resultSetPrototype);
                },
                'Student\Model\StateTable' => function($sm) {                    
                    $tableGateway = $sm->get('StateTableGateway');
                    $table = new StateTable($tableGateway);
                    return $table;
                },
                'StateTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new State());
                    return new TableGateway('state', $dbAdapter, null, $resultSetPrototype);
                },
                'Student\Model\StudentTable' => function($sm) {                    
                    $tableGateway = $sm->get('StudentTableGateway');
                    $table = new StudentTable($tableGateway);
                    return $table;
                },
                'StudentTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Student());
                    return new TableGateway('student', $dbAdapter, null, $resultSetPrototype);
                },
                'Student\Model\StudentVerbalTable' => function($sm) {                    
                    $tableGateway = $sm->get('StudentVerbalTableGateway');
                    $table = new StudentVerbalTable($tableGateway);
                    return $table;
                },
                'StudentVerbalTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new StudentVerbal());
                    return new TableGateway('student_reg_verbal_ability', $dbAdapter, null, $resultSetPrototype);
                },
                'Student\Model\StudentStatusTable' => function($sm) {                    
                    $tableGateway = $sm->get('StudentStatusTableGateway');
                    $table = new StudentStatusTable($tableGateway);
                    return $table;
                },
                'StudentStatusTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new StudentStatus());
                    return new TableGateway('student_status', $dbAdapter, null, $resultSetPrototype);
                },
                'Questionarie\Model\QuestionTable' => function($sm) {                    
                    $tableGateway = $sm->get('QuestionTableGateway');
                    $table = new QuestionTable($tableGateway);
                    return $table;
                },
                'QuestionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Question());
                    return new TableGateway('question', $dbAdapter, null, $resultSetPrototype);
                },
                        
                'Questionarie\Model\QuestionOptionTable' => function($sm) {                    
                    $tableGateway = $sm->get('QuestionOptionTableGateway');
                    $table = new QuestionOptionTable($tableGateway);
                    return $table;
                },
                'QuestionOptionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new QuestionOption());
                    return new TableGateway('questions_options', $dbAdapter, null, $resultSetPrototype);
                },
                
            ),
        );
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

}
