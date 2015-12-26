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
        $center_types = $this->getServiceLocator()->get('Config');
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

            if ($form->isValid() || 1) {
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
     * function to edit Center
     * @return type
     */
    public function editAction() {

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('center', array('action' => 'add'));
        }
        $page = (int) $this->params()->fromRoute('page', 0);

        $session = new Container('User');
        $center_group = $this->getCenterGroupTable()->fetchAll(false, $order_by = 'id', $order = 'DESC', $searchText = NULL);
        $center_types = $this->getServiceLocator()->get('Config');
        $regionList = $this->getRegionTable()->fetchAll();
        $form = new CenterForm($center_group, $center_types['center_types'], $regionList);

        $center_detail = $this->getCenterTable()->getCenter($id);
        $cityList = $this->getCityTable()->getRegionCity($center_detail->state);
        $form->get('city')->setAttribute('options', $cityList);
        $form->get('email')->setAttribute('readonly', 'readonly');
        $form->get('id')->setValue($id);
        $form->bind($center_detail);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $center = new Center();
            $data = $request->getPost();
            $form->setInputFilter($center->getInputFilter());
            $form->setData($data);
            if (isset($data->city) && $data->city !== '') {
                $cityData = $this->getCityTable()->getCityId($data->city);
                $cityValue[$cityData->id] = $cityData->name;
                $form->get('city')->setAttribute('options', $cityValue);
            }

            if (isset($data->group_name) && $data->group_name !== '') {
                $centerGroup = $this->getCenterGroupTable()->getCenterGroup($data->group_name);
                $data->group_id = $centerGroup[0]['id'];
            }
            if ($form->isValid() || 1) {
                //$center->exchangeArray($form->getData());
                $data->updated_date = time();
                $data->updated_by = $session->offsetGet('userId');

                $validatorEmail = new \Zend\Validator\Db\NoRecordExists(
                        array(
                    'table' => 'center',
                    'field' => 'email',
                    'adapter' => $this->getAdapter(),
                    'exclude' => array(
                        'field' => 'id',
                        'value' => $id,
                    )
                        )
                );
                $validatorName = new \Zend\Validator\Db\NoRecordExists(
                        array(
                    'table' => 'center',
                    'field' => 'name',
                    'adapter' => $this->getAdapter(),
                    'exclude' => array(
                        'field' => 'id',
                        'value' => $id,
                    )
                        )
                );
                if ($validatorEmail->isValid(trim($data->email)) && $validatorName->isValid(trim($data->name))) {
                    $no_duplicate_data = 1;
                } else {
                    $flashMessage = $this->flashMessenger()->getErrorMessages();
                    if (empty($flashMessage)) {
                        $this->flashMessenger()->setNamespace('error')->addMessage('Email Id or Center Name already Exists.');
                    }
                    $no_duplicate_data = 0;
                }


                if ($no_duplicate_data == 1) {
                    $nonFile = $request->getPost()->toArray();
                    // Make file object for center image
                    $firstQueImg = $this->params()->fromFiles('center_logo');
                    if ($firstQueImg['name'] != '') {
                        // Upload center image
                        $adapter = new \Zend\File\Transfer\Adapter\Http();
                        $uploadDirPath = './public/uploads/center_logo/';
                        $adapter->setDestination($uploadDirPath);

                        $adapter->receive($firstQueImg['name']);
                        $piece = explode('.', $firstQueImg['name']);
                        //asd($piece);
                        $piece[0] = $piece[0] . '_' . time();
                        $file_name = implode('.', $piece);
                        // Define variable for image upload directory
                        $firstQueImgPath = $uploadDirPath . $file_name;
                        $firstimgsavepath = 'uploads/center_logo/' . $file_name;
                        $firstimgsavepath2 = 'uploads/center_logo/resized_logo/' . $file_name;
                        // Now rename the center images
                        rename($uploadDirPath . $firstQueImg['name'], $firstQueImgPath);

                        // RESIZE Image                    
                        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
                        $thumb1 = $thumbnailer->create("public/" . $firstimgsavepath, $options = array());
                        $thumb1->resize(100, 100);
                        $thumb1->save("public/" . $firstimgsavepath2);
                        $data['center_logo'] = $file_name;
                    } else {
                        $data['center_logo'] = $center_detail->center_logo;
                    }
                    $this->getCenterTable()->updateCenter($data);

                    if ($data->submit == 'Publish') {
                        //$this->getUserTable()->updateUserCenter($data, $center_detail->email);
                        $where_user_email = array('email' => $center_detail->email);
                        $check_user_exists = $this->getUserTable()->isUserExists($where_user_email);
                        if (count($check_user_exists) > 0) {
                            $this->getUserTable()->updateUserCenter($data, $center_detail->email);
                            $this->flashMessenger()->setNamespace('success')->addMessage('Center updated successfully');
                        } else {
                            $data->created_date = time();
                            $data->created_by = $session->offsetGet('userId');
                            /* service call for center replication on LA */
                            $this->getCenterReplicationService($data, $center_detail->id, $type = 'draftPublish');
                        }
                    } else {
                        $this->getServiceLocator()->get('Zend\Log')->info('Center updated successfully by user ' . $session->offsetGet('userId'));
                        $this->flashMessenger()->setNamespace('success')->addMessage('Center updated successfully');
                    }
                    return $this->redirect()->toRoute('center');
                }
            }
        }
        return array('form' => $form, 'center_detail' => $center_detail, 'id' => $id, 'page' => $page);
    }

    /**
     * function to delete Center
     * @return type
     */
    public function deleteAction() {
        
    }

    /**
     * function for center view
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

    public function sendActivationLink($email, $hashValueReturn) {
        $session = new Container('User');
        $adminEmail = $session->offsetGet('email');
        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        $requesturi = $this->getRequest()->getUri();
        $ref_file_name = explode('center', $_SERVER['HTTP_REFERER']);
        $this->baseUrl = $requesturi->getHost() . $renderer->basePath();
        //$url = $requesturi->getScheme() . '://' . $this->baseUrl . '/login/activate/' . $hashValueReturn;
        $url = $ref_file_name[0] . 'login/activate/' . $hashValueReturn;
        //subject of the email
        $subject = 'Activate Account';
        //body of the email
        $reciever_message = "Dear User $email\n\n 
                             This mail is a system generated email to activate your account...\n\n";
        $reciever_message .= "Please visit $url to activate your account and please set your password.\n\n";
        $reciever_message .= "Note:-\n 1.This link will automatically expire after 72 hours.\n 2.In case your link is expired please write to $adminEmail.\n 3.Please do not reply to this email.";
        $this->getRecoverEmailTable()->sendEmailToUser($subject, $reciever_message, $email);
    }

}

?>
