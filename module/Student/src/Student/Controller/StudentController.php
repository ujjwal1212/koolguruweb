<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Student\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Student\Form\StudentForm;
use Student\Model\Degree;
use Student\Model\DegreeTable;
use Student\Model\State;
use Student\Model\StateTable;
use Student\Model\Student;
use Student\Model\StudentTable;

class StudentController extends AbstractActionController{
    
    protected $DegreeTable;
    protected $StateTable;
    protected $StudentTable;
    protected $adapter;
    
    public function getAdapter() {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }
    
    public function getDegreeTable() {
        if (!$this->DegreeTable) {
            $sm = $this->getServiceLocator();
            $this->DegreeTable = $sm->get('Student\Model\DegreeTable');
        }
        return $this->DegreeTable;
    }
    
    public function getStateTable() {
        if (!$this->StateTable) {     
            $sm = '';
            $sm = $this->getServiceLocator();            
            $this->StateTable = $sm->get('Student\Model\StateTable');            
        }
        return $this->StateTable;
    }
    
    public function getStudentTable() {
        if (!$this->StudentTable) {     
            $sm = '';
            $sm = $this->getServiceLocator();            
            $this->StudentTable = $sm->get('Student\Model\StudentTable');            
        }
        return $this->StudentTable;
    }
    
    public function indexAction() {
        $enableTab = array();
        $enableTabContent = array();
        $degreeList = array();
        $stateList = array();
        $verbalQuestions = array();
        
        $degreeList = $this->getDegreeTable()->getDegreeList();        
        $stateList = $this->getStateTable()->getStateList();
        
        $studentId = '';
        $studentId = 7;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();            
            if(empty($data['student_id'])){
                $studentId = $this->getStudentTable()->saveStudent($data);
            }else{
                $studentId = $this->getStudentTable()->updateStudent($data,$studentId);
            }
        }
        
        $form = new StudentForm('studentForm',$degreeList,$stateList);
        if($studentId != ''){
            $studentDet = $this->getStudentTable()->getSudent($studentId);            
            $form->bind($studentDet);
        }
        $form->get('student_id')->setValue($studentId);
        
        $enableTab = $this->getStudentTable()->getEnableTabList($studentId);
        $enableTabContent = $this->getStudentTable()->getEnableTabContentList($studentId);
        
        if($enableTabContent[1] == 1){
            $t['title'] = 'Which city is the capital of Uttar Pradesh ?';
            $t['options'] = array('kanpur','Indore','Allahabad','Lucknow');
            $t['correct'] = 4;
            $verbalQuestions[] = $t;
            
            $t['title'] = '(2+5)^2 = ?';
            $t['options'] = array(4,64,49,81);
            $t['correct'] = 3;
            $verbalQuestions[] = $t;
            
            $t['title'] = 'Where United Nations Exist ?';
            $t['options'] = array('USA','China','UK','Russia');
            $t['correct'] = 1;
            $verbalQuestions[] = $t;
        }
        //asd($enableTabContent,0);
        //asd($verbalQuestions);
        
        return array(
            'form' => $form,'enableTab' => $enableTab,
            'enableTabContent' => $enableTabContent,
            'verbalQuestions' => $verbalQuestions
        );
    }
    
}
