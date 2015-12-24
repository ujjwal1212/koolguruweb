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
        $studentId = '';
        //$studentId = 2;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $studentId = $this->getStudentTable()->saveStudent($data);
        }
        $degreeList = $this->getDegreeTable()->getDegreeList();        
        $stateList = $this->getStateTable()->getStateList();
        $form = new StudentForm('studentForm',$degreeList,$stateList);
        
        $enableTab = $this->getStudentTable()->getEnableTabList($studentId);
        $enableTabContent = $this->getStudentTable()->getEnableTabContentList($studentId);
        //$enableTabContent = array(TRUE,TRUE,0,0);
        
        return array(
            'form' => $form,'enableTab' => $enableTab,
            'enableTabContent' => $enableTabContent
        );
    }
    
}
