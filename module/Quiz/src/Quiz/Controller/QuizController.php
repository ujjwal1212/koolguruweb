<?php

namespace Quiz\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Db\Sql\Select;
use Quiz\Form\SearchForm;
use Quiz\Form\QuizForm;
use Subject\Model\Subject;
use Subject\Model\SubjectTable;
use Questionarie\Model\Level;
use Questionarie\Model\LevelTable;
use Subject\Model\Category;
use Subject\Model\CategoryTable;
use Package\Model\Package;
use Package\Model\PackageTable;
use Quiz\Model\Quiz;
use Quiz\Model\QuizTable;
use Quiz\Model\Quizlevel;
use Quiz\Model\QuizlevelTable;

class QuizController extends AbstractActionController {

    protected $adapter;
    protected $subjectTable;
    protected $packageTable;
    protected $CourseTable;
    protected $levelTable;
    protected $CategoryTable;
    protected $CoursePackageTable;
    protected $QuizTable;
    protected $QuizlevelTable;

    public function getAdapter() {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }
    
    public function getQuizTable() {
        if (!$this->QuizTable) {
            $sm = $this->getServiceLocator();
            $this->QuizTable = $sm->get('Quiz\Model\QuizTable');
        }
        return $this->QuizTable;
    }
    
    public function getQuizlevelTable() {
        if (!$this->QuizlevelTable) {
            $sm = $this->getServiceLocator();
            $this->QuizlevelTable = $sm->get('Quiz\Model\QuizlevelTable');
        }
        return $this->QuizlevelTable;
    }
    
    public function getCategoryTable() {
        if (!$this->CategoryTable) {
            $sm = $this->getServiceLocator();
            $this->CategoryTable = $sm->get('Subject\Model\CategoryTable');
        }
        return $this->CategoryTable;
    }
    
    public function getlevelTable() {
        if (!$this->levelTable) {
            $sm = $this->getServiceLocator();
            $this->levelTable = $sm->get('Questionarie\Model\LevelTable');
        }
        return $this->levelTable;
    }
    
    public function getsubjectTable() {
        if (!$this->subjectTable) {
            $sm = $this->getServiceLocator();
            $this->subjectTable = $sm->get('Subject\Model\SubjectTable');
        }
        return $this->subjectTable;
    }

    public function getPackageTable() {
        if (!$this->packageTable) {
            $sm = $this->getServiceLocator();
            $this->packageTable = $sm->get('Package\Model\PackageTable');
        }
        return $this->packageTable;
    }

    public function getCourseTable() {
        if (!$this->CourseTable) {
            $sm = $this->getServiceLocator();
            $this->CourseTable = $sm->get('Student\Model\CourseTable');
        }
        return $this->CourseTable;
    }

    public function getCoursePackageTable() {
        if (!$this->CoursePackageTable) {
            $sm = $this->getServiceLocator();
            $this->CoursePackageTable = $sm->get('Package\Model\coursePackageTable');
        }
        return $this->CoursePackageTable;
    }

    /**
     * Action for Manage Package listing page
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction() {
        if (isset($_REQUEST['page'])) {
            $page = $_REQUEST['page'];
        } else {
            $page = 1;
        }
        $form = new SearchForm('form_search');
        $request = $this->getRequest();

        if ($request->isGet()) {
            $data = $request->getQuery();
            $form->getInputFilter()->get('list_count')->setRequired(false);
            $form->getInputFilter()->get('list_count')->setAllowEmpty(true);
            $list_count = isset($data['list_count']) && trim($data['list_count']) != '' ? $data['list_count'] : 10;
            $order_by = isset($data['order_by']) && trim($data['order_by']) != '' ? $data['order_by'] : 'id';
            $order = isset($data['order']) && trim($data['order']) != '' ? $data['order'] : Select::ORDER_DESCENDING;
            $searchText = isset($data['search_box_value']) ? trim($data['search_box_value']) : Null;
            $form->get('list_count')->setValue($list_count);
            $form->get('order_by')->setValue($order_by);
            $form->get('order')->setValue($order);
            $form->setData($data);
            if ($form->isValid()) {
                $paginator = $this->getQuizTable()->fetchAll(true, $order_by, $order, $searchText);
            }
        } else {
            // grab the paginator from the CenterTable
            $paginator = $this->getQuizTable()->fetchAll(true, $order_by, $order, $searchText);
        }
        $row_count = $paginator->getTotalItemCount();
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', $page));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage($list_count);
        $paginator->setPageRange(5);
        if (isset($data['page'])) {
            unset($data['page']);
        }
        if ($request->isXmlHttpRequest()) {
            $this->layout('layout/ajax');
        }
        $errorMsg = $this->flashMessenger()->getCurrentMessagesFromNamespace('error');
        $successMsg = $this->flashMessenger()->getCurrentMessagesFromNamespace('success');
        if (isset($errorMsg) && is_array($errorMsg) && !empty($errorMsg)) {
            $errorMsg = $errorMsg[0];
        }

        return new ViewModel(array(
            'paginator' => $paginator,
            'row_count' => $row_count,
            'list_count' => $list_count,
            'page' => $page,
            'order_by' => $order_by,
            'order' => $order,
            'data' => $data,
            'form' => $form,
            'isAjax' => $request->isXmlHttpRequest(),
            'errorMsg' => $errorMsg,
            'successMsg' => $successMsg
        ));
    }
    
    
    public function getchapterAction() {
        $student = array();
        $subject = $_GET['subject'];
        $chapters = $this->getsubjectTable()->getSubjectsChapter($subject);
        
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $this->layout('layout/ajax');
        }
        
        echo json_encode($chapters);
        return false;
    }

    /**
     * Action for adding new Package
     * @return type
     */
    public function addAction() {
        $session = new Container('User');
        //$courseList = $this->getCourseTable()->getCourseDropdown();
        
        // Get subject list
        $subList = $this->getsubjectTable()->getSubjectList();
        $subjects = array();
        foreach($subList as $sub){
            $subjects[$sub['id']] = $sub['title'];
        }
        
        $levelList = array();
        $levelList = $this->getlevelTable()->getLevelDropdown(); 
        
        $que_category = array();
        $que_category = $this->getCategoryTable()->getCategoryList();
        
        $form = new QuizForm('QuizForm',$subjects);
        $form->get('code')->setValue('xxx-xxx-xxx');
        $form->get('code')->setAttribute('readonly', TRUE);
        $form->get('created_at')->setValue(time());
        $form->get('created_by')->setValue($session->offsetGet('userId'));
        $form->get('updated_at')->setValue(time());
        $form->get('updated_by')->setValue($session->offsetGet('userId'));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $quiz = new Quiz();
            $data = $request->getPost();            
            $form->setInputFilter($quiz->getInputFilter());
            $form->setData($data);
            if ($form->isValid() || 1) {
                $validatorName = new \Zend\Validator\Db\NoRecordExists(
                        array(
                    'table' => 'quiz',
                    'field' => 'title',
                    'adapter' => $this->getAdapter()
                        )
                );
                if ($validatorName->isValid(trim($quiz->title))) {
                    $no_duplicate_data = 1;
                } else {
                    $flashMessage = $this->flashMessenger()->getErrorMessages();
                    if (empty($flashMessage)) {
                        $this->flashMessenger()->setNamespace('error')->addMessage('Quiz Name already Exists.');
                    }
                    $no_duplicate_data = 0;
                }
                if ($no_duplicate_data == 1) {
                    
                    $quizList = $this->getQuizTable()->fetchAll(false, 'id', 'ASC', '');                    
                    $quiz->exchangeArray($form->getData());
                    $id = 0;
                    $quizCode = 'KGQZ-000-';
                    foreach ($quizList as $quizId) {
                        $id = $quizId['id'];
                    }
                    $quizCode .= '' . intval($id) + 1;
                    $quiz->code = $quizCode;
                    $data->created_at = time();
                    $data->created_by = $session->offsetGet('userId');
                    $data->updated_at = time();
                    $data->updated_by = $session->offsetGet('userId');
                    $quizId = $this->getQuizTable()->saveQuiz($quiz);
                    //asd($data);
                    // Now create data for saving quiz level mapping
                    $quizlevel = array();
                    //$quizId = 1;
                    for($i=1; $i<=$data['rowcount']; $i++){
                        $t = array();
                        $t['quiz_id'] =  $quizId;
                        $t['level_id'] =  $data['level_'.$i];
                        $t['category_id'] =  $data['category_'.$i];
                        $t['ques_nos'] =  $data['count_'.$i];
                        $t['created_at'] = time();
                        $t['created_by'] = $session->offsetGet('userId');
                        $t['updated_at'] = time();
                        $t['updated_by'] = $session->offsetGet('userId');
                        $quizlevel[] = $t;
                    }
                    if(!empty($quizlevel)){
                        foreach($quizlevel as $data){
                            $this->getQuizlevelTable()->saveQuizLevel($data);
                        }
                    }
                    
//                        $this->getServiceLocator()->get('Zend\Log')->info('Level created successfully by user ' . $session->offsetGet('userId'));
                    $this->flashMessenger()->setNamespace('success')->addMessage('Quiz created successfully');
                    return $this->redirect()->toRoute('quiz');
                }
            }
        }

        return array('form' => $form,'levelList'=>$levelList,'que_category'=>$que_category);
    }

    /**
     * function to edit Package
     * @return type
     */
    public function editAction() {
        $levelList = array();
        $levelList = $this->getlevelTable()->getLevelDropdown(); 
        
        $que_category = array();
        $que_category = $this->getCategoryTable()->getCategoryList();
        
        $subList = $this->getsubjectTable()->getSubjectList();
        $subjects = array();
        foreach($subList as $sub){
            $subjects[$sub['id']] = $sub['title'];
        }
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('quiz', array('action' => 'add'));
        }
        $page = (int) $this->params()->fromRoute('page', 0);

        $session = new Container('User');
        $courseList = $this->getCourseTable()->getCourseDropdown();
        $form = new QuizForm('QuizForm', $subjects);
        $form->get('code')->setAttribute('readonly', TRUE);
        $quiz = $this->getQuizTable()->getQuiz($id);        
        $form->get('id')->setValue($id);
        $form->bind($quiz);
        
        
        $quizlevel = array();
        $quizlevel = $this->getQuizlevelTable()->getQuizlevelByQuiz($id);
        
        

        $request = $this->getRequest();
        if ($request->isPost()) {
            $quiz = new Quiz();
            $data = $request->getPost();
            $quiz->exchangeArray($data);
            $form->setInputFilter($quiz->getInputFilter());
            $form->setData($data);
            
            if ($form->isValid() || 1) {
                $no_duplicate_data = 1;
                if ($no_duplicate_data == 1) {
                    $quizList = $this->getQuizTable()->fetchAll(false, 'id', 'ASC', '');                    
                    $data->updated_date = time();
                    $data->updated_by = $session->offsetGet('userId');                    
                    $Id = $this->getQuizTable()->saveQuiz($quiz);
                    
                    //asd($data);
                    // Now create data for saving quiz level mapping
                    $quizlevel = array();
                    $quizId = $quiz->id;
                    $data = $_POST;
                    //asd($data);
                    for($i=1; $i<=$data['rowcount']; $i++){
                        $t = array();
                        $t['quiz_id'] =  $quizId;
                        $t['level_id'] =  $data['level_'.$i];
                        $t['category_id'] =  $data['category_'.$i];
                        $t['ques_nos'] =  $data['count_'.$i];
                        $t['created_at'] = time();
                        $t['created_by'] = $session->offsetGet('userId');
                        $t['updated_at'] = time();
                        $t['updated_by'] = $session->offsetGet('userId');
                        $quizlevel[] = $t;
                    }
                    // First delete old entry
                    $this->getQuizlevelTable()->deleteQuizLevel($quizId);
                    
                    if(!empty($quizlevel)){
                        foreach($quizlevel as $data){
                            $this->getQuizlevelTable()->saveQuizLevel($data);
                        }
                    }
                    
//                        $this->getServiceLocator()->get('Zend\Log')->info('Level created successfully by user ' . $session->offsetGet('userId'));
                    $this->flashMessenger()->setNamespace('success')->addMessage('Quiz updated successfully');

                    return $this->redirect()->toRoute('quiz');
                }
            }
        }
        return array('form' => $form, 'id' => $id, 'page' => $page,'quiz' => $quiz,'levelList' => $levelList,'que_category'=>$que_category,'quizlevel'=>$quizlevel);
    }

    
}
