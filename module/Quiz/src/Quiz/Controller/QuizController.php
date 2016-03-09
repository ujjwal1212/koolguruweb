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

use Package\Model\Package;
use Package\Model\PackageTable;

class QuizController extends AbstractActionController {

    protected $adapter;
    protected $subjectTable;
    protected $packageTable;
    protected $CourseTable;
    protected $CoursePackageTable;

    public function getAdapter() {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
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
                $paginator = $this->getPackageTable()->fetchAll(true, $order_by, $order, $searchText);
            }
        } else {
            // grab the paginator from the CenterTable
            $paginator = $this->getPackageTable()->fetchAll(true, $order_by, $order, $searchText);
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
        
        $form = new QuizForm('QuizForm',$subjects);
        $form->get('code')->setValue('xxx-xxx-xxx');
        $form->get('code')->setAttribute('readonly', TRUE);
        $form->get('created_at')->setValue(time());
        $form->get('created_by')->setValue($session->offsetGet('userId'));
        $form->get('updated_at')->setValue(time());
        $form->get('updated_by')->setValue($session->offsetGet('userId'));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $package = new Package();
            $data = $request->getPost();
            $form->setInputFilter($package->getInputFilter());
            $form->setData($data);
            if ($form->isValid() || 1) {
                $validatorName = new \Zend\Validator\Db\NoRecordExists(
                        array(
                    'table' => 'package',
                    'field' => 'title',
                    'adapter' => $this->getAdapter()
                        )
                );
                if ($validatorName->isValid(trim($package->title))) {
                    $no_duplicate_data = 1;
                } else {
                    $flashMessage = $this->flashMessenger()->getErrorMessages();
                    if (empty($flashMessage)) {
                        $this->flashMessenger()->setNamespace('error')->addMessage('Package Name already Exists.');
                    }
                    $no_duplicate_data = 0;
                }
                if ($no_duplicate_data == 1) {
                    $packageList = $this->getPackageTable()->fetchAll(false, 'id', 'ASC', '');
                    $package->exchangeArray($form->getData());
                    $id = 0;
                    $packageCode = 'KGPKG-000-';
                    foreach ($packageList as $packageId) {
                        $id = $packageId['id'];
                    }
                    $packageCode .= '' . intval($id) + 1;
                    $imagePath = $this->uploadImage($packageCode);
                    $package->image_path = $imagePath;
                    $package->code = $packageCode;
                    $data->created_at = time();
                    $data->created_by = $session->offsetGet('userId');
                    $data->updated_at = time();
                    $data->updated_by = $session->offsetGet('userId');
                    $packageId = $this->getPackageTable()->savePackage($package);
                    if (isset($data->courseId)) {
                        $this->getCoursePackageTable()->savePackageCourse($data, $packageId);
                    }
//                        $this->getServiceLocator()->get('Zend\Log')->info('Level created successfully by user ' . $session->offsetGet('userId'));
                    $this->flashMessenger()->setNamespace('success')->addMessage('Package created successfully');
                    return $this->redirect()->toRoute('package');
                }
            }
        }

        return array('form' => $form);
    }

    /**
     * function to edit Package
     * @return type
     */
    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('package', array('action' => 'add'));
        }
        $page = (int) $this->params()->fromRoute('page', 0);

        $session = new Container('User');
        $courseList = $this->getCourseTable()->getCourseDropdown();
        $form = new PackageForm('PackageForm', $courseList);
        $form->get('code')->setAttribute('readonly', TRUE);
        $package = $this->getPackageTable()->getPackage($id);
        $form->get('id')->setValue($id);
        $form->bind($package);
        $courseMapData = $this->getCoursePackageTable()->getCourseMapData($id);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $package = new Package();
            $data = $request->getPost();
            $package->exchangeArray($data);
            $form->setInputFilter($package->getInputFilter());
            $form->setData($data);

            if ($form->isValid() || 1) {
                $validatorName = new \Zend\Validator\Db\NoRecordExists(
                        array(
                    'table' => 'package',
                    'field' => 'title',
                    'adapter' => $this->getAdapter(),
                    'exclude' => array(
                        'field' => 'id',
                        'value' => $id,
                    )
                        )
                );
                if ($validatorName->isValid(trim($package->title))) {
                    $no_duplicate_data = 1;
                } else {
                    $flashMessage = $this->flashMessenger()->getErrorMessages();
                    if (empty($flashMessage)) {
                        $this->flashMessenger()->setNamespace('error')->addMessage('Package Name already Exists.');
                    }
                    $no_duplicate_data = 0;
                }
                if ($no_duplicate_data == 1) {
                    $packageList = $this->getPackageTable()->fetchAll(false, 'id', 'ASC', '');
                    if ($_FILES['image_path']['tmp_name']) {
                        if ($package->image_path) {
                            unlink($package->image_path);
                        }
                        $imagePath = $this->uploadImage($package->code);
                        $package->image_path = $imagePath;
                    } else {
                        $package->image_path = NULL;
                    }
                    $data->updated_date = time();
                    $data->updated_by = $session->offsetGet('userId');

                    $packageId = $this->getPackageTable()->savePackage($package);
                    if (isset($data->courseId)) {
                        $this->getCoursePackageTable()->savePackageCourse($data, $id);
                    }
//                        $this->getServiceLocator()->get('Zend\Log')->info('Level created successfully by user ' . $session->offsetGet('userId'));
                    $this->flashMessenger()->setNamespace('success')->addMessage('Package updated successfully');

                    return $this->redirect()->toRoute('package');
                }
            }
        }
        return array('form' => $form, 'id' => $id, 'page' => $page, 'courseMapData' => $courseMapData);
    }

    public function uploadImage($code) {
        if (isset($_FILES['image_path']['name']) && $_FILES['image_path']['name'] != '') {
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
     * function to delete Package
     * @return type
     */
    public function deleteAction() {
        
    }

    /**
     * function for Package view
     * @return type
     */
    public function viewAction() {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('package', array('action' => 'add'));
        }
        $page = (int) $this->params()->fromRoute('page', 0);

        $session = new Container('User');

        $package = $this->getPackageTable()->getPackageDetails($id);
        $courseMapData = $this->getCoursePackageTable()->getCourseMapData($id);
        return array(
            'package' => $package,
            'courseMapData' => $courseMapData
        );
    }

}
