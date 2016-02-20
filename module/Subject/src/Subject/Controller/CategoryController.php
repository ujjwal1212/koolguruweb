<?php

namespace Subject\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Db\Sql\Select;
use Subject\Form\SearchForm;
use Subject\Form\CategoryForm;
use Subject\Model\Category;
use Subject\Model\CategoryTable;

class CategoryController extends AbstractActionController {

    protected $adapter;
    protected $CategoryTable;

    public function getAdapter() {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }

    public function getCategoryTable() {
        if (!$this->CategoryTable) {
            $sm = $this->getServiceLocator();
            $this->CategoryTable = $sm->get('Subject\Model\CategoryTable');
        }
        return $this->CategoryTable;
    }

    /**
     * Action for Manage Category listing page
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
                $paginator = $this->getCategoryTable()->fetchAll(true, $order_by, $order, $searchText);
            }
        } else {
            // grab the paginator from the CenterTable
            $paginator = $this->getCategoryTable()->fetchAll(true, $order_by, $order, $searchText);
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
     * Action for adding new Category
     * @return type
     */
    public function addAction() {
        $session = new Container('User');
        $form = new CategoryForm('CategoryForm');

        $form->get('created_date')->setValue(time());
        $form->get('created_by')->setValue($session->offsetGet('userId'));
        $form->get('updated_date')->setValue(time());
        $form->get('updated_by')->setValue($session->offsetGet('userId'));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $category = new Category();
            $data = $request->getPost();
            $form->setInputFilter($category->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $validatorName = new \Zend\Validator\Db\NoRecordExists(
                        array(
                    'table' => 'category',
                    'field' => 'title',
                    'adapter' => $this->getAdapter()
                        )
                );
                if ($validatorName->isValid(trim($category->title))) {
                    $no_duplicate_data = 1;
                } else {
                    $flashMessage = $this->flashMessenger()->getErrorMessages();
                    if (empty($flashMessage)) {
                        $this->flashMessenger()->setNamespace('error')->addMessage('Category Name already Exists.');
                    }
                    $no_duplicate_data = 0;
                }

                if ($no_duplicate_data == 1) {
                    $category->exchangeArray($form->getData());
                    $data->created_date = time();
                    $data->created_by = $session->offsetGet('userId');
                    $data->updated_date = time();
                    $data->updated_by = $session->offsetGet('userId');

                    $questionId = $this->getCategoryTable()->saveCategory($category);
//                        $this->getServiceLocator()->get('Zend\Log')->info('Level created successfully by user ' . $session->offsetGet('userId'));
                    $this->flashMessenger()->setNamespace('success')->addMessage('Category created successfully');
                    return $this->redirect()->toRoute('category');
                }
            }
        }

        return array('form' => $form);
    }

    /**
     * function to edit Category
     * @return type
     */
    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('category', array('action' => 'add'));
        }
        $page = (int) $this->params()->fromRoute('page', 0);

        $session = new Container('User');
        $form = new CategoryForm('CategoryForm');
        $category = $this->getCategoryTable()->getCategory($id);
        $form->get('id')->setValue($id);
        $form->bind($category);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $category = new Category();
            $data = $request->getPost();
            $category->exchangeArray($data);
            $form->setInputFilter($category->getInputFilter());
            $form->setData($data);

            if ($form->isValid()) {
                $validatorName = new \Zend\Validator\Db\NoRecordExists(
                        array(
                    'table' => 'category',
                    'field' => 'title',
                    'adapter' => $this->getAdapter(),
                    'exclude' => array(
                        'field' => 'id',
                        'value' => $id,
                    )
                        )
                );
                if ($validatorName->isValid(trim($category->title))) {
                    $no_duplicate_data = 1;
                } else {
                    $flashMessage = $this->flashMessenger()->getErrorMessages();
                    if (empty($flashMessage)) {
                        $this->flashMessenger()->setNamespace('error')->addMessage('Category Name already Exists.');
                    }
                    $no_duplicate_data = 0;
                }

                if ($no_duplicate_data == 1) {
                    $data->updated_date = time();
                    $data->updated_by = $session->offsetGet('userId');

                    $categoryId = $this->getCategoryTable()->saveCategory($category);
//                        $this->getServiceLocator()->get('Zend\Log')->info('Level created successfully by user ' . $session->offsetGet('userId'));
                    $this->flashMessenger()->setNamespace('success')->addMessage('Category updated successfully');

                    return $this->redirect()->toRoute('category');
                }
            }
        }
        return array('form' => $form, 'id' => $id, 'page' => $page);
    }

    /**
     * function to delete Category
     * @return type
     */
    public function deleteAction() {
        
    }

    /**
     * function for Category view
     * @return type
     */
    public function viewAction() {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('category', array('action' => 'add'));
        }
        $page = (int) $this->params()->fromRoute('page', 0);

        $session = new Container('User');

        $category = $this->getCategoryTable()->getCategoryDetails($id);
        return array(
            'category' => $category,
        );
    }

}
