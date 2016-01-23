<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Questionarie\Form\SearchForm;
use Admin\Form\CarrierpathForm;
use Admin\Model\Carrierpath;
use Zend\Db\Sql\Select;

class AdminController extends AbstractActionController {

    protected $adapter;
    protected $StudentMobileTable;
    protected $CarrierpathTable;
    protected $SendqueryTable;

    public function getAdapter() {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }

    public function getCarrierpathTable() {
        if (!$this->CarrierpathTable) {
            $sm = $this->getServiceLocator();
            $this->CarrierpathTable = $sm->get('Admin\Model\CarrierpathTable');
        }
        return $this->CarrierpathTable;
    }

    public function getStudentMobileTable() {
        if (!$this->StudentMobileTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->StudentMobileTable = $sm->get('Student\Model\StudentMobileTable');
        }
        return $this->StudentMobileTable;
    }

    public function getSendqueryTable() {
        if (!$this->SendqueryTable) {
            $sm = $this->getServiceLocator();
            $this->SendqueryTable = $sm->get('Application\Model\SendqueryTable');
        }
        return $this->SendqueryTable;
    }

    public function indexAction() {
        return new ViewModel(array(
        ));
    }

    public function carrierPathAction() {
        $successMsg = $this->flashMessenger()->getCurrentMessagesFromNamespace('success');
        $errorMsg = $this->flashMessenger()->getCurrentMessagesFromNamespace('error');

        return new ViewModel(array(
            'success' => $successMsg,
            'error' => $errorMsg
        ));
    }

    public function addcarrierpathAction() {
        $session = new Container('User');
        $form = new CarrierpathForm('carrier path');

        $form->get('created_date')->setValue(time());
        $form->get('created_by')->setValue($session->offsetGet('userId'));
        $form->get('updated_date')->setValue(time());
        $form->get('updated_by')->setValue($session->offsetGet('userId'));
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();
            $carrierpath = new Carrierpath();
            $form->setInputFilter($carrierpath->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $carrierpath->exchangeArray($form->getData());
                $data->created_date = time();
                $data->created_by = $session->offsetGet('userId');
                $data->updated_date = time();
                $data->updated_by = $session->offsetGet('userId');

                $Id = $this->getCarrierpathTable()->saveCarrierpath($carrierpath);
                $this->flashMessenger()->setNamespace('success')->addMessage('Carrier Path created successfully');
                return $this->redirect()->toRoute('carrierpath');
            }
        }

        return array('form' => $form);
    }

    public function contactQueryAction() {
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
                $paginator = $this->getSendqueryTable()->fetchAll(true, $order_by, $order, $searchText);
            }
        } else {
            // grab the paginator from the CenterTable
            $paginator = $this->getSendqueryTable()->fetchAll(true, $order_by, $order, $searchText);
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

    public function studentRegisterAction() {
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
                $paginator = $this->getStudentMobileTable()->fetchAll(true, $order_by, $order, $searchText);
            }
        } else {
            // grab the paginator from the CenterTable
            $paginator = $this->getStudentMobileTable()->fetchAll(true, $order_by, $order, $searchText);
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

    public function updatequerystatusAction() {
        $id = $_GET['id'];
        $query_data = $this->getSendqueryTable()->updateQueryStatus($id);

        return true;
    }

}
