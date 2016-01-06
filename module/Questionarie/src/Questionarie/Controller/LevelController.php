<?php

namespace Questionarie\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Questionarie\Model\Level;
use Zend\Session\Container;
use Questionarie\Form\SearchForm;
use Questionarie\Form\LevelForm;
use Zend\Db\Sql\Select;

class LevelController extends AbstractActionController {

    protected $levelTable;
    protected $adapter;

    public function getAdapter() {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }

    public function getLevelTable() {
        if (!$this->levelTable) {
            $sm = $this->getServiceLocator();
            $this->levelTable = $sm->get('Questionarie\Model\LevelTable');
        }
        return $this->levelTable;
    }

    /**
     * Action for Manage Level listing page
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
                $paginator = $this->getLevelTable()->fetchAll(true, $order_by, $order, $searchText);
            }
        } else {
            // grab the paginator from the CenterTable
            $paginator = $this->getLevelTable()->fetchAll(true, $order_by, $order, $searchText);
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
     * Action for adding new Level
     * @return type
     */
    public function addAction() {
        $session = new Container('User');
        $form = new LevelForm('level');

        $form->get('created_date')->setValue(time());
        $form->get('created_by')->setValue($session->offsetGet('userId'));
        $form->get('updated_date')->setValue(time());
        $form->get('updated_by')->setValue($session->offsetGet('userId'));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $level = new Level();
            $data = $request->getPost();
            $form->setInputFilter($level->getInputFilter());
            $form->setData($data);

            if ($form->isValid()) {
                $level->exchangeArray($form->getData());
                $data->created_date = time();
                $data->created_by = $session->offsetGet('userId');
                $data->updated_date = time();
                $data->updated_by = $session->offsetGet('userId');

                $validatorName = new \Zend\Validator\Db\NoRecordExists(
                        array(
                    'table' => 'level',
                    'field' => 'name',
                    'adapter' => $this->getAdapter()
                        )
                );
                if ($validatorName->isValid(trim($data->name))) {
                    $no_duplicate_data = 1;
                } else {
                    $flashMessage = $this->flashMessenger()->getErrorMessages();
                    if (empty($flashMessage)) {
                        $this->flashMessenger()->setNamespace('error')->addMessage('Level with this Name already Exists.');
                    }
                    $no_duplicate_data = 0;
                }

                if ($no_duplicate_data == 1) {
                    $levelId = $this->getLevelTable()->saveLevel($level);
//                        $this->getServiceLocator()->get('Zend\Log')->info('Level created successfully by user ' . $session->offsetGet('userId'));
                    $this->flashMessenger()->setNamespace('success')->addMessage('Level created successfully');


                    return $this->redirect()->toRoute('level');
                }
            }
        }
        return array('form' => $form);
    }

    /**
     * function to edit Level
     * @return type
     */
    public function editAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('level', array('action' => 'add'));
        }
        $page = (int) $this->params()->fromRoute('page', 0);

        $session = new Container('User');


        $form = new LevelForm('level');

        $levelDetail = $this->getLevelTable()->getLevel($id);

        $form->get('id')->setValue($id);
        $form->bind($levelDetail);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $level = new Level();
            $data = $request->getPost();
            $level->exchangeArray($data);
            $form->setInputFilter($level->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $data->updated_date = time();
                $data->updated_by = $session->offsetGet('userId');

                $validatorName = new \Zend\Validator\Db\NoRecordExists(
                        array(
                    'table' => 'level',
                    'field' => 'name',
                    'adapter' => $this->getAdapter(),
                    'exclude' => array(
                        'field' => 'id',
                        'value' => $id,
                    )
                        )
                );
                if ($validatorName->isValid(trim($data->name))) {
                    $no_duplicate_data = 1;
                } else {
                    $flashMessage = $this->flashMessenger()->getErrorMessages();
                    if (empty($flashMessage)) {
                        $this->flashMessenger()->setNamespace('error')->addMessage('Level Name already Exists.');
                    }
                    $no_duplicate_data = 0;
                }
                if ($no_duplicate_data == 1) {
                    $levelId = $this->getLevelTable()->saveLevel($level);
//                        $this->getServiceLocator()->get('Zend\Log')->info('Level created successfully by user ' . $session->offsetGet('userId'));
                    $this->flashMessenger()->setNamespace('success')->addMessage('Level updated successfully');


                    return $this->redirect()->toRoute('level');
                }
            }
        }
        return array('form' => $form, 'levelDetail' => $levelDetail, 'id' => $id, 'page' => $page);
    }

    /**
     * function to delete Center
     * @return type
     */
    public function deleteAction() {
        
    }

    /**
     * function for Level view
     * @return type
     */
    public function viewAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $msg = (int) $this->params()->fromRoute('msg', 0);
        $center = $this->getCenterTable()->getCenter($id);

        $region = NULL;
        if (isset($center->state) && $center->state != '') {
            $region = $this->getRegionTable()->getRegion($center->state);
            $region = $region->name;
        }

        $city = NULL;
        if (isset($center->city) && $center->city != '') {
            $cityId = $this->getCityTable()->getCityId($center->city);
            $city = $cityId->name;
        }

        if (isset($msg) && $msg == 1) {
            $email = $center->email;
            //function to delete row from activation with $center->email
            $this->getRecoverEmailTable()->deleteActivationEmail($email);
            $hashValueReturn = $this->getUserTable()->saveActivationEmail($email);
            $this->sendActivationLink($email, $hashValueReturn);
            $flashMessage = $this->flashMessenger()->getCurrentSuccessMessages();
            if (empty($flashMessage)) {
                $this->flashMessenger()->setNamespace('success')->addMessage('Activation Email has been sent successfully');
            }
            return $this->redirect()->toRoute('center');
        }

        $satellite_centers = $this->getCenterTable()->getSatelliteCenter($id);
        $qualification = $this->getCenterTable()->getCenterQualificationRegion($id);
        $data = array(
            'center' => $center,
            'satellite_centers' => $satellite_centers,
            'qualification' => $qualification,
            'region' => $region,
            'city' => $city
        );
        return array(
            'id' => $id,
            'data' => $data,
        );
    }

}
