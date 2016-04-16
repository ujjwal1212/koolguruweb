<?php

namespace Subject\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Db\Sql\Select;
use Subject\Form\SearchForm;
use Subject\Form\SubjectForm;
use Subject\Model\Subject;
use Subject\Model\SubjectTable;
use Student\Model\Course;
use Student\Model\CourseTable;
use Student\Model\Coursesubject;
use Student\Model\CoursesubjectTable;

class SubjectController extends AbstractActionController {

    protected $adapter;
    protected $subjectTable;
    protected $courseTable;
    protected $coursesubjectTable;

    public function getAdapter() {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }
    
    public function getCourseSubjectTable() {
        if (!$this->coursesubjectTable) {
            $sm = $this->getServiceLocator();
            $this->coursesubjectTable = $sm->get('Subject\Model\CoursesubjectTable');
        }
        return $this->coursesubjectTable;
    }

    public function getSubjectTable() {
        if (!$this->subjectTable) {
            $sm = $this->getServiceLocator();
            $this->subjectTable = $sm->get('Subject\Model\SubjectTable');
        }
        return $this->subjectTable;
    }
    
    public function getCourseTable() {
        if (!$this->courseTable) {
            $sm = $this->getServiceLocator();
            $this->courseTable = $sm->get('Student\Model\CourseTable');
        }
        return $this->courseTable;
    }

    /**
     * Action for Manage subject listing page
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
                $paginator = $this->getSubjectTable()->fetchAll(true, $order_by, $order, $searchText);
            }
        } else {
            // grab the paginator from the CenterTable
            $paginator = $this->getSubjectTable()->fetchAll(true, $order_by, $order, $searchText);
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

    /**
     * Action for adding new subject
     * @return type
     */
    public function addAction() {
        $courseList = $this->getCourseTable()->getCourseDropdown();
        
        $session = new Container('User');
        $form = new SubjectForm('SubjectForm',$courseList);
        $form->get('code')->setValue('xxx-xxx-xxx');
        $form->get('code')->setAttribute('readonly', TRUE);
        $form->get('created_at')->setValue(time());
        $form->get('created_by')->setValue($session->offsetGet('userId'));
        $form->get('updated_at')->setValue(time());
        $form->get('updated_by')->setValue($session->offsetGet('userId'));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $subject = new Subject();
            $data = $request->getPost();
            $form->setInputFilter($subject->getInputFilter());
            $form->setData($data);
            
            if ($form->isValid()) {
                //asd($subject);
                $validatorName = new \Zend\Validator\Db\NoRecordExists(
                        array(
                    'table' => 'subjects',
                    'field' => 'title',
                    'adapter' => $this->getAdapter()
                        )
                );
                if ($validatorName->isValid(trim($subject->title))) {
                    $no_duplicate_data = 1;
                } else {
                    $flashMessage = $this->flashMessenger()->getErrorMessages();
                    if (empty($flashMessage)) {
                        $this->flashMessenger()->setNamespace('error')->addMessage('Subject Name already Exists.');
                    }
                    $no_duplicate_data = 0;
                }

                if ($no_duplicate_data == 1) {
                    $subjectList = $this->getSubjectTable()->fetchAll(false, 'id', 'ASC', '');
                    $subject->exchangeArray($form->getData());
                    
                    $id = 0;
                    $subjectCode = 'KGSUB-000-';
                    foreach ($subjectList as $subjectId) {
                        $id = $subjectId['id'];
                    }
                    $subjectCode .= '' . intval($id) + 1;  
                    $imagePath = '';
                    if(isset($_FILES) && isset($_FILES['image_path']) && $_FILES['image_path']['name']!=''){
                        $imagePath = $this->uploadImage($subjectCode);
                    }                    
                    $subject->image_path = $imagePath;
                    $subject->code = $subjectCode;
                    $data->created_at = time();
                    $data->created_by = $session->offsetGet('userId');
                    $data->updated_at = time();
                    $data->updated_by = $session->offsetGet('userId');
                    $subId = '';
                    $subId = $this->getSubjectTable()->saveSubject($subject);                    
                    $courseSubjectMapData = array();
                    $courseSubjectMapData['subject_id'] =  $subId;
                    $courseSubjectMapData['course_id'] =  $data->course_id;
                    $courseSubjectMapData['created_at'] = time();
                    $courseSubjectMapData['created_by'] = $session->offsetGet('userId');
                    $courseSubjectMapData['updated_at'] = time();
                    $courseSubjectMapData['updated_by'] = $session->offsetGet('userId');
                    
                    $this->getCourseSubjectTable()->saveCaurseSubjectMap($courseSubjectMapData);
                    
                    
//                        $this->getServiceLocator()->get('Zend\Log')->info('Level created successfully by user ' . $session->offsetGet('userId'));
                    $this->flashMessenger()->setNamespace('success')->addMessage('Subject created successfully');
                    return $this->redirect()->toRoute('subject');
                }
            }
        }

        return array('form' => $form);
    }

    /**
     * function to edit Subject
     * @return type
     */
    public function editAction() {
        $courseList = $this->getCourseTable()->getCourseDropdown();
        
        
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('subject', array('action' => 'add'));
        }
        $page = (int) $this->params()->fromRoute('page', 0);

        $session = new Container('User');
        $form = new SubjectForm('SubjectForm',$courseList);
        $form->get('code')->setAttribute('readonly', TRUE);
        $subject = $this->getSubjectTable()->getSubject($id);
        
        $subjects = array();
        $subjects = $this->getSubjectTable()->getSubjecCoursetDetails($id);       
        $form->get('id')->setValue($id);
        if(!empty($subjects)){
            $form->get('course_id')->setValue($subjects[0]['course_id']);
        }
        $form->get('id')->setValue($id);
        $form->bind($subject);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $subject = new Subject();
            $data = $request->getPost();
            $subject->exchangeArray($data);
            $form->setInputFilter($subject->getInputFilter());
            $form->setData($data);

            if ($form->isValid()) {
                $validatorName = new \Zend\Validator\Db\NoRecordExists(
                        array(
                    'table' => 'subjects',
                    'field' => 'title',
                    'adapter' => $this->getAdapter(),
                    'exclude' => array(
                        'field' => 'id',
                        'value' => $id,
                    )
                        )
                );
                if ($validatorName->isValid(trim($subject->title))) {
                    $no_duplicate_data = 1;
                } else {
                    $flashMessage = $this->flashMessenger()->getErrorMessages();
                    if (empty($flashMessage)) {
                        $this->flashMessenger()->setNamespace('error')->addMessage('Subject Name already Exists.');
                    }
                    $no_duplicate_data = 0;
                }
                if ($no_duplicate_data == 1) {
                    $subjectList = $this->getSubjectTable()->fetchAll(false, 'id', 'ASC', '');
                    
                    if ($_FILES['image_path']['tmp_name']) {
                        if ($subject->image_path) {
                            unlink($subject->image_path);
                        }
                        $imagePath = $this->uploadImage($subject->code);
                        $subject->image_path = $imagePath;
                    }else{
                        $subject->image_path = NULL;
                    }
                    $data->updated_date = time();
                    $data->updated_by = $session->offsetGet('userId');

                    $subjectId = $this->getSubjectTable()->saveSubject($subject);
                    $courseSubjectMapData = array();
                    if(empty($subjects)){                        
                        $courseSubjectMapData['subject_id'] =  $subjectId;
                        $courseSubjectMapData['course_id'] =  $data->course_id;
                        $courseSubjectMapData['created_at'] = time();
                        $courseSubjectMapData['created_by'] = $session->offsetGet('userId');
                        $courseSubjectMapData['updated_at'] = time();
                        $courseSubjectMapData['updated_by'] = $session->offsetGet('userId');                    
                        $this->getCourseSubjectTable()->saveCaurseSubjectMap($courseSubjectMapData);
                    }else{
                        $courseSubjectMapData['course_id'] =  $data->course_id;                       
                        $courseSubjectMapData['updated_at'] = time();
                        $courseSubjectMapData['updated_by'] = $session->offsetGet('userId');
                        $this->getCourseSubjectTable()->updateCaurseSubjectMap($courseSubjectMapData,$subjectId);
                    }                    
                    
//                        $this->getServiceLocator()->get('Zend\Log')->info('Level created successfully by user ' . $session->offsetGet('userId'));
                    $this->flashMessenger()->setNamespace('success')->addMessage('Subject updated successfully');

                    return $this->redirect()->toRoute('subject');
                }
            }
        }
        return array('form' => $form, 'id' => $id, 'page' => $page);
    }

    public function uploadImage($code) {
        if (isset($_FILES['image_path']['name'])) {
            $fileNameArr = explode('.', $_FILES['image_path']['name']);
            $fileName = $code . '.' . $fileNameArr[1];
            $targetDir = realpath(__DIR__ . '../../../../../../') . '/data/images/';
            $targetFile = $targetDir . $fileName;
            move_uploaded_file($_FILES["image_path"]["tmp_name"], $targetFile);
            return $targetFile;
        } else {
            return NULL;
        }
    }

    /**
     * function to delete Category
     * @return type
     */
    public function deleteAction() {
        
    }

    /**
     * function for Subject view
     * @return type
     */
    public function viewAction() {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('subject', array('action' => 'add'));
        }
        $page = (int) $this->params()->fromRoute('page', 0);

        $session = new Container('User');

        $subject = $this->getSubjectTable()->getSubjectDetails($id);
        return array(
            'subject' => $subject,
        );
    }

}
