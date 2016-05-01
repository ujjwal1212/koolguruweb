<?php

namespace Chapter\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Db\Sql\Select;
use Subject\Form\SearchForm;
use Chapter\Form\ChapterForm;
use Subject\Model\Subject;
use Subject\Model\SubjectTable;
use Chapter\Model\Chapter;
use Chapter\Model\ChapterTable;
use Chapter\Model\Subjectchapter;
use Chapter\Model\SubjectchapterTable;



class ChapterController extends AbstractActionController {

    protected $adapter;
    protected $subjectTable;
    protected $chapterTable;
    protected $subjectchapterTable;

    public function getAdapter() {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }

    public function getSubjectTable() {
        if (!$this->subjectTable) {
            $sm = $this->getServiceLocator();
            $this->subjectTable = $sm->get('Subject\Model\SubjectTable');
        }
        return $this->subjectTable;
    }
    
    public function getChapterTable() {
        if (!$this->chapterTable) {
            $sm = $this->getServiceLocator();
            $this->chapterTable = $sm->get('Chapter\Model\ChapterTable');
        }
        return $this->chapterTable;
    }
    
    public function getSubjectchapterTable() {
        if (!$this->subjectchapterTable) {
            $sm = $this->getServiceLocator();
            $this->subjectchapterTable = $sm->get('Chapter\Model\SubjectchapterTable');
        }
        return $this->subjectchapterTable;
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
                $paginator = $this->getChapterTable()->fetchAll(true, $order_by, $order, $searchText);
            }
        } else {
            // grab the paginator from the CenterTable
            $paginator = $this->getChapterTable()->fetchAll(true, $order_by, $order, $searchText);            
        }
        //$paginator1 = $paginator;
        $allchapters = $this->getChapterTable()->getAllChapter();
        $subjects = array();
        foreach($allchapters as $chapter){   
            $temp = $this->getSubjectTable()->getChapterSubjects($chapter['id']); 
            $subjects[$chapter['id']] = $temp;
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
            'successMsg' => $successMsg,
            'subjects' => $subjects
        ));
    }

    /**
     * Action for adding new subject
     * @return type
     */
    public function addAction() {
        $session = new Container('User');
        
        // Get the subject list
        $subjectList = $this->getSubjectTable()->getSubjectList();
        
        $form = new ChapterForm('chapter',$subjectList);        
        $form->get('created_at')->setValue(time());
        $form->get('created_by')->setValue($session->offsetGet('userId'));
        $form->get('updated_at')->setValue(time());
        $form->get('updated_by')->setValue($session->offsetGet('userId'));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $chapter = new Chapter();
            //$subjectChapter = new Subjectchapter();
            $data = $request->getPost();
            //asd($data);
            $form->setInputFilter($chapter->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $validatorName = new \Zend\Validator\Db\NoRecordExists(
                        array(
                            'table' => 'chapters',
                            'field' => 'title',
                            'adapter' => $this->getAdapter()
                        )
                );
                if ($validatorName->isValid(trim($chapter->title))) {
                    $no_duplicate_data = 1;
                } else {
                    $flashMessage = $this->flashMessenger()->getErrorMessages();
                    if (empty($flashMessage)) {
                        $this->flashMessenger()->setNamespace('error')->addMessage('Chapter Name already Exists.');
                    }
                    $no_duplicate_data = 0;
                }

                if ($no_duplicate_data == 1) {
                    $chaptercode = $this->getChapterTable()->getNewChapterCode();
                    
                    $chapter->exchangeArray($form->getData());
                    $chapter->code = $chaptercode;
                    
                    $id = 0;                    
                    $data->created_at = time();
                    $data->created_by = $session->offsetGet('userId');
                    $data->updated_at = time();
                    $data->updated_by = $session->offsetGet('userId');
                    
                    $chapterId = $this->getChapterTable()->saveChapter($chapter);
                    

                    $subjectChapter = array();
                    $subjectChapter['chapter_id'] = $chapterId;
                    $subjectChapter['created_at'] = time();
                    $subjectChapter['created_by'] = $session->offsetGet('userId');
                    $subjectChapter['updated_at'] = time();
                    $subjectChapter['updated_by'] = $session->offsetGet('userId');
                    
                    if(isset($data['subject']) && count($data['subject']) > 0){
                        foreach($data['subject'] as $subjectid){
                            $subjectChapter['subject_id'] = $subjectid;
                            $this->getSubjectchapterTable()->saveMapping($subjectChapter);
                        }
                    }
                    
                    
                    
//                        $this->getServiceLocator()->get('Zend\Log')->info('Level created successfully by user ' . $session->offsetGet('userId'));
                    $this->flashMessenger()->setNamespace('success')->addMessage('Chapter created successfully');
                    return $this->redirect()->toRoute('chapter');
                }
            }
        }

        return array('form' => $form,'subjectList' => $subjectList);
    }

    /**
     * function to edit Subject
     * @return type
     */
    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        // Get the subject list
        $subjectList = $this->getSubjectTable()->getSubjectList();
        $subjects = array();
        
        $subjects = array();
        $temp = $this->getSubjectTable()->getChapterSubjects($id);        
        
        foreach($temp as $data){
            $subjects[] = $data['subject_id'];
        }

        if (!$id) {
            return $this->redirect()->toRoute('chapter', array('action' => 'add'));
        }
        $page = (int) $this->params()->fromRoute('page', 0);

        $session = new Container('User');
        $form = new ChapterForm('chapter');
        
        $chapter = $this->getChapterTable()->getChapter($id);
        $form->get('id')->setValue($id);
        $form->bind($chapter);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $chapter = new Chapter();
            $data = $request->getPost();
            $chapter->exchangeArray($data);
            $form->setInputFilter($chapter->getInputFilter());
            $form->setData($data);

            if ($form->isValid()) {
                $validatorName = new \Zend\Validator\Db\NoRecordExists(
                        array(
                    'table' => 'chapters',
                    'field' => 'title',
                    'adapter' => $this->getAdapter(),
                    'exclude' => array(
                        'field' => 'id',
                        'value' => $id,
                    )
                        )
                );
                if ($validatorName->isValid(trim($chapter->title))) {
                    $no_duplicate_data = 1;
                } else {
                    $flashMessage = $this->flashMessenger()->getErrorMessages();
                    if (empty($flashMessage)) {
                        $this->flashMessenger()->setNamespace('error')->addMessage('Chapter Name already Exists.');
                    }
                    $no_duplicate_data = 0;
                }
                if ($no_duplicate_data == 1) {
                    $chapterList = $this->getChapterTable()->fetchAll(false, 'id', 'ASC', '');                    
                    
                    $data->updated_date = time();
                    $data->updated_by = $session->offsetGet('userId');

                    $chapterId = $this->getChapterTable()->saveChapter($chapter);
                    
                    
                    foreach($subjects as $subject){                        
                        $this->getSubjectchapterTable()->deleteMapping($subject,$id);
                    }
                    $subjectChapter = array();
                    $subjectChapter['chapter_id'] = $id;
                    $subjectChapter['created_at'] = time();
                    $subjectChapter['created_by'] = $session->offsetGet('userId');
                    $subjectChapter['updated_at'] = time();
                    $subjectChapter['updated_by'] = $session->offsetGet('userId');
                    
                    if(isset($data['subject']) && count($data['subject']) > 0){
                        foreach($data['subject'] as $subjectid){
                            $subjectChapter['subject_id'] = $subjectid;
                            $this->getSubjectchapterTable()->saveMapping($subjectChapter);
                        }
                    }
                    
                    
//                        $this->getServiceLocator()->get('Zend\Log')->info('Level created successfully by user ' . $session->offsetGet('userId'));
                    $this->flashMessenger()->setNamespace('success')->addMessage('Chapter updated successfully');

                    return $this->redirect()->toRoute('chapter');
                }
            }
        }
        return array('form' => $form, 'id' => $id, 'page' => $page,'subjectList'=>$subjectList,'subjects'=>$subjects);
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
