<?php

/*
 * Controller Class for User module
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Form\UserRoleForm;
use User\Model\Role;
use User\Model\RoleTable;
use User\Form\Filter\UserFilter;
use User\Form\SearchForm;
use Zend\Db\Sql\Select;
use ZF2AuthAcl\Model\UserTable;
use Zend\Authentication\Adapter\DbTable;
use Zend\Session\Container;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Expression;
use ZF2AuthAcl\Utility\UserPassword;

class UserRoleController extends AbstractActionController {

    protected $UserTable;
    protected $adapter;
    protected $RoleTable;
    protected $RolePermissionTable;
    protected $PermissionTable;

    /**
     * Function to get DB adapter
     * @return
     */
    public function getAdapter() {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }

    /**
     * Function to get instance of user table
     * @return type
     */
    public function getUserTable() {
        if (!$this->UserTable) {
            $sm = $this->getServiceLocator();
            $this->UserTable = $sm->get('ZF2AuthAcl\Model\UserTable');
        }
        return $this->UserTable;
    }

    /**
     * Function to get instance of resource table
     * @return type
     */
    public function getRolePermissionTable() {
        if (!$this->RolePermissionTable) {
            $sm = $this->getServiceLocator();
            $this->RolePermissionTable = $sm->get('User\Model\RolePermissionTable');
        }
        return $this->RolePermissionTable;
    }

    /**
     * Function to get instance of permission table
     * @return type
     */
    public function getPermissionTable() {
        if (!$this->PermissionTable) {
            $sm = $this->getServiceLocator();
            $this->PermissionTable = $sm->get('User\Model\PermissionTable');
        }
        return $this->PermissionTable;
    }

    /**
     * Function to get instance of role table
     * @return type
     */
    public function getRoleTable() {
        if (!$this->RoleTable) {
            $sm = $this->getServiceLocator();
            $this->RoleTable = $sm->get('User\Model\RoleTable');
        }
        return $this->RoleTable;
    }

    /**
     * Default Action of UserController
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
            $order_by = isset($data['order_by']) && trim($data['order_by']) != '' ? $data['order_by'] : 'rid';
            $order = isset($data['order']) && trim($data['order']) != '' ? $data['order'] : Select::ORDER_DESCENDING;
            $searchText = isset($data['search_box_value']) ? trim($data['search_box_value']) : Null;
            $form->get('list_count')->setValue($list_count);
            $form->get('order_by')->setValue($order_by);
            $form->get('order')->setValue($order);
            $form->setData($data);
            if ($form->isValid()) {
                $paginator = $this->getRoleTable()->fetchAll(true, $order_by, $order, $searchText);
            }
        } else {
            // grab the paginator from the UnitTable
            $paginator = $this->getRoleTable()->fetchAll(true, $order_by, $order, $searchText);
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
        return new ViewModel(array(
            'paginator' => $paginator,
            'data' => $data,
            'list_count' => $list_count,
            'order_by' => $order_by,
            'order' => $order,
            'row_count' => $row_count,
            'form' => $form,
            'page' => $page,
            'isAjax' => $request->isXmlHttpRequest(),
        ));
    }

    public function array_2d_to_1d($input_array) {
        $output_array = array();

        for ($i = 0; $i < count($input_array); $i++) {
            for ($j = 0; $j < count($input_array[$i]); $j++) {
                $output_array[] = $input_array[$i][$j];
            }
        }

        return $output_array;
    }

    /**
     * Function to add new role
     */
    public function addAction() {
        $session = new Container('User');
        $roleCode = $session->offsetGet('roleCode');
        $userRoles = $this->getUserTable()->userRole($roleCode);
        $systemRoles = $this->getRoleTable()->fetchSystemRoles();
        $request = $this->getRequest();
        $form = new UserRoleForm('user_role', $systemRoles);

        $readOnlyResourceArr = array();
        $readOnlyPermissionArr = array();
        $fullAccessResourceArr = array();

        $fullAccessPermissionArr = array();
        $disableResourceArr = array();
        $disablePermissionArr = array();

        $readPermissionIdArr = array();
        $fullPermissionIdArr = array();
        $disablePermissionIdArr = array();

        if ($request->isPost()) {
            $data = $request->getPost();

            $form->setData($data);
            //if ($form->isValid()) {
            $user_role = $data['role_name'];
            $user_role_code = $data['role_code'];
            if (isset($data['parent_role']) && $data['parent_role'] != '') {
                $resourceArr = $this->getRolePermissionTable()->getRoleResource($data['parent_role']);
                $resourceDetails = Array();
                if (!empty($resourceArr)) {
                    foreach ($resourceArr as $resource) {
                        $details = explode('*', $resource);
                        $resourceDetails[] = $details[1];
                    }
                }
                foreach ($resourceDetails as $i) {
                    if ($i == $data[$i]) {
                        if ($data['permissionRadio_' . $i] == 'readOnly_' . $i) {
                            $readOnlyResourceArr[] = $i;
                        }
                        if ($data['permissionRadio_' . $i] == 'fullAccess_' . $i) {
                            $fullAccessResourceArr[] = $i;
                        }
                        if ($data['permissionRadio_' . $i] == 'disable_' . $i) {
                            $disableResourceArr[] = $i;
                        }
                    }
                }


                if (!empty($readOnlyResourceArr)) {
                    foreach ($readOnlyResourceArr as $read) {
                        $readOnlyPermissionArr[] = $this->getPermissionTable()->getReadResourcePermission($read, $data['parent_role']);
                    }
                    $readOnlyPermissionArr[] = $this->getPermissionTable()->getFullResourcePermission('1', $data['parent_role']);
                }

                if (!empty($fullAccessResourceArr)) {
                    foreach ($fullAccessResourceArr as $full) {
                        $fullAccessPermissionArr[] = $this->getPermissionTable()->getFullResourcePermission($full, $data['parent_role']);
                    }
                    $fullAccessPermissionArr[] = $this->getPermissionTable()->getFullResourcePermission('1', $data['parent_role']);
                }
                if (!empty($disableResourceArr)) {
                    $disablePermissionArr = $this->getPermissionTable()->getFullResourcePermission('1', $data['parent_role']);
                }

                $readPermissionIdArr = $this->array_2d_to_1d($readOnlyPermissionArr);
                $fullPermissionIdArr = $this->array_2d_to_1d($fullAccessPermissionArr);
            }
            if ($user_role != '' || $user_role_code != '') {
                $validatorRoleName = new \Zend\Validator\Db\NoRecordExists(
                        array(
                    'table' => 'role',
                    'field' => 'role_name',
                    'adapter' => $this->getAdapter()
                        )
                );

                $validatorRoleCode = new \Zend\Validator\Db\NoRecordExists(
                        array(
                    'table' => 'role',
                    'field' => 'role_code',
                    'adapter' => $this->getAdapter()
                        )
                );

                if ($validatorRoleName->isValid($user_role) && $validatorRoleCode->isValid($user_role_code)) {
                    $session = new Container('User');
                    $createdUserRoleId = $this->getRoleTable()->saveUserRole($data);


                    if (isset($createdUserRoleId) && $createdUserRoleId != '') {

                        if (!empty($readPermissionIdArr)) {
                            foreach ($readPermissionIdArr as $readPermission) {
                                // save read only role's permission
                                $this->RolePermissionTable->saveRolePermission($createdUserRoleId, $readPermission['id']);
                            }
                        }

                        if (!empty($fullPermissionIdArr)) {
                            foreach ($fullPermissionIdArr as $fullPermission) {
                                // save full access role's permission
                                $this->RolePermissionTable->saveRolePermission($createdUserRoleId, $fullPermission['id']);
                            }
                        }

                        if (!empty($disablePermissionArr)) {
                            foreach ($disablePermissionArr as $disablePermission) {
                                // save disable role's permission
                                $this->RolePermissionTable->saveRolePermission($createdUserRoleId, $disablePermission['id']);
                            }
                        }
                        $this->getServiceLocator()->get('Zend\Log')->info('User Role with Id ' . $createdUserRoleId . ' added by user ' . $session->offsetGet('userId'));
                    }
                    $this->flashMessenger()->setNamespace('success')->addMessage('User Role created successfully');
                    return $this->redirect()->toRoute('userrole', array());
                } else {
                    $flashMessage = $this->flashMessenger()->getErrorMessages();
                    if (empty($flashMessage)) {
                        $this->flashMessenger()->setNamespace('error')->addMessage('User Role Code Already Exist'
                        );
                    }
                }
            }
            //}
        }
        return array('form' => $form);
    }

    public function getresourcepermissionAction() {
        $viewModel = new ViewModel(array());
        $request = $this->getRequest();
        $data = $request->getPost();
        $roleId = $data->role;
        $userRoleDetails = $this->getRoleTable()->getUserRole($roleId);
        $roleCode = $userRoleDetails->role_code;
        $resourceArr = $this->getRolePermissionTable()->getRoleResource($roleId);
        $resourceDetails = Array();
        $resourceIdArr = Array();
        $onlyReadResource = Array();
        if (!empty($resourceArr)) {
            foreach ($resourceArr as $resource) {
                $details = explode('\\', $resource);
                $resourceId = explode('*', $resource);
                $resourceIdArr[] = $resourceId[1];
                $resourceDetails[] = $details[2];
            }

            foreach ($resourceIdArr as $res) {
                $readArrString = array();
                $fullArrString = array();
                $readResourceArr = $this->getPermissionTable()->getReadResourcePermission($res, $roleId);
                $fullResourceArr = $this->getPermissionTable()->getFullResourcePermission($res, $roleId);
                foreach ($readResourceArr as $readArr) {
                    $readArrString[] = $readArr['permission_name'];
                }
                $flag = true;
                foreach ($fullResourceArr as $fullArr) {
                    if (in_array($fullArr['permission_name'], $readArrString)) {
                        $flag = true;
                    } else {
                        $flag = false;
                        break;
                    }
                }
                if ($flag) {
                    $onlyReadResource[] = $res;
                }
            }
//            $fullResourceArr1 = $this->getPermissionTable()->getFullResourcePermission(3, 2);
//            $readResourceArr1 = $this->getPermissionTable()->getReadResourcePermission(3, 2);
////            $fullResourceArr2 = $this->getPermissionTable()->getFullResourcePermission(4, 2);
////            $readResourceArr2 = $this->getPermissionTable()->getReadResourcePermission(4, 2);
////
//            echo "<pre />";
//            print_r($fullResourceArr1);
//            print_r($readResourceArr1);
//////            print_r($fullResourceArr2);
//////            print_r($readResourceArr2);
//            die;


            foreach ($resourceIdArr as $rid) {
                if (in_array($rid, $onlyReadResource)) {
                    $key = array_search($rid, $resourceIdArr);
                    $resourceName = $resourceDetails[$key];
                    $resourceDetails[$key] = $resourceName . '*Read';
                }
            }

            //$resourceDetails = array_push($resourceDetails, $resourceName);
        }
        $viewModel->setTerminal(true);
        $this->layout('layout/empty');
        $response = $this->getResponse();
        $response->setContent(json_encode($resourceDetails));
        return $response;
    }

    /**
     * Function to edit a role
     * @return type
     */
    public function editAction() {
        $session = new Container('User');
        $id = (int) $this->params('id');
        if (!$id) {
            return $this->redirect()->toRoute('userrole', array('action' => 'index'));
        }

        $uniqueResourceArr = array();
        $parentUniqueResourceNameArr = array();
        $parentUniqueResourceIdArr = array();
        $readResourceIdArr = array();
        $fullResourceIdArr = array();
        $disableResourceIdArr = array();

        $fullAccess = array();
        $readAccess = array();
        $noAccess = array();

        $readOnlyPermissionArr = array();
        $fullAccessPermissionArr = array();

        $readOnlyResourceArr = array();
        $fullAccessResourceArr = array();
        $disableResourceArr = array();

        $onlyReadResource = array();

        $userRoleDetails = $this->getRoleTable()->getUserRole($id);
        $roleCode = $userRoleDetails->role_code;
        $roleResourcePermissionArr = $this->getRolePermissionTable()->getRoleResourcePermission($id);
        if (isset($userRoleDetails) && $userRoleDetails->parent_role_code == 0) {
            $userRoleDetails->parent_role_code = $userRoleDetails->rid; // if role is parent_role then assign its role_id to it
        }
        $parentRoleResourcePermissionArr = $this->getRolePermissionTable()->getRoleResourcePermission($userRoleDetails->parent_role_code);
        //get unique role's resource
        if (!empty($roleResourcePermissionArr)) {
            foreach ($roleResourcePermissionArr as $resourcePermission) {
                if (!in_array($resourcePermission['resource_id'], $uniqueResourceArr)) {
                    $uniqueResourceArr[] = $resourcePermission['resource_id'];
                    $details = explode('\\', $resourcePermission['resource_name']);
                    if ($details[2] != 'Index') {
                        $uniqueResourceNameArr[] = $details[2] . '*' . $resourcePermission['resource_id'];
                    }
                }
                if (preg_match('/^index*(\w+)/i', $resourcePermission['permission_name']) || preg_match('/^view(\w+)/i', $resourcePermission['permission_name']) || $resourcePermission['permission_name'] == 'view' || $resourcePermission['permission_name'] == 'index') {
                    $readResourceIdArr[] = $resourcePermission['resource_id'];
                } else if (preg_match('/^add*(\w+)/i', $resourcePermission['permission_name']) || preg_match('/^edit*(\w+)/i', $resourcePermission['permission_name']) || preg_match('/^publish*(\w+)/i', $resourcePermission['permission_name']) || preg_match('/^create*(\w+)/i', $resourcePermission['permission_name']) || $resourcePermission['permission_name'] == 'add' || $resourcePermission['permission_name'] == 'edit') {
                    $fullResourceIdArr[] = $resourcePermission['resource_id'];
                } else {
                    $disableResourceIdArr[] = $resourcePermission['resource_id'];
                }
            }

//            echo "<pre />";
//            print_r($readResourceIdArr);
//            die;
        }


        //get unique parent role's resource
        if (!empty($parentRoleResourcePermissionArr)) {
            foreach ($parentRoleResourcePermissionArr as $parentResourcePermission) {
                if (!in_array($parentResourcePermission['resource_id'], $parentUniqueResourceIdArr)) {
                    $parentUniqueResourceIdArr[] = $parentResourcePermission['resource_id'];
                    $parentDetails = explode('\\', $parentResourcePermission['resource_name']);
                    if ($parentDetails[2] != 'Index') {
                        $parentUniqueResourceNameArr[] = $parentDetails[2] . '*' . $parentResourcePermission['resource_id'];
                    }
                }
            }
        }

        foreach ($parentUniqueResourceIdArr as $res) {
            $readResourceArr = $this->getPermissionTable()->getReadResourcePermission($res, $userRoleDetails->parent_role_code);
            $fullResourceArr = $this->getPermissionTable()->getFullResourcePermission($res, $userRoleDetails->parent_role_code);
            if ($readResourceArr === $fullResourceArr) {
                $onlyReadResource[] = $res;
            }
        }


        // classify resource on the basis of its accessibilty like fullAccess, readAccess and noAccess
        if (!empty($uniqueResourceArr)) {
            foreach ($uniqueResourceArr as $i) {
                if ((in_array($i, $readResourceIdArr) && in_array($i, $fullResourceIdArr)) || in_array($i, $disableResourceIdArr)) {
                    $fullAccess[] = $i;
                } else if ((in_array($i, $readResourceIdArr) && !in_array($i, $fullResourceIdArr)) || !in_array($i, $disableResourceIdArr)) {
                    $readAccess[] = $i;
                } else if (!in_array($i, $readResourceIdArr) && !in_array($i, $fullResourceIdArr) && in_array($i, $disableResourceIdArr)) {
                    $noAccess[] = $i;
                }
            }
        }
        // merge read & full access permission in to a single array
        $accessibleResourceArr = array_merge($fullAccess, $readAccess);
        // disable resources array
        $outputNoAccess = array_merge(array_diff($parentUniqueResourceIdArr, $accessibleResourceArr), array_diff($accessibleResourceArr, $parentUniqueResourceIdArr), $noAccess);

        $systemRoles = $this->getRoleTable()->fetchSystemRoles();
        $form = new UserRoleForm('user_role', $systemRoles);
        $form->get('submit')->setAttribute('value', 'Update');
        $form->get('parent_role')->setValue($userRoleDetails->parent_role_code);
        $form->get('parent_role')->setAttribute('readonly', true);
        $form->bind($userRoleDetails);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $userRole = new Role();
            $data = $request->getPost();
            $userRole->rid = $id;
            $userRole->role_name = $data['role_name'];
            $userRole->role_code = strtolower($data['role_code']);
            $userRole->status = $data['status'];
            $userRole->parent_role = $data['parent_role'];
            $form->setData($data);
            $user_role = $data['role_name'];
            $user_role_code = $data['role_code'];
            if (isset($data['parent_role']) && $data['parent_role'] != '') {
                $resourceArr = $this->getRolePermissionTable()->getRoleResource($data['parent_role']);
                $resourceDetails = Array();
                if (!empty($resourceArr)) {
                    foreach ($resourceArr as $resource) {
                        $details = explode('*', $resource);
                        $resourceDetails[] = $details[1];
                    }
                }

                foreach ($resourceDetails as $i) {
                    if ($i == $data[$i]) {
                        if ($data['permissionRadio_' . $i] == 'readOnly_' . $i) {
                            $readOnlyResourceArr[] = $i;
                        }
                        if ($data['permissionRadio_' . $i] == 'fullAccess_' . $i) {
                            $fullAccessResourceArr[] = $i;
                        }
                        if ($data['permissionRadio_' . $i] == 'disable_' . $i) {
                            $disableResourceArr[] = $i;
                        }
                    }
                }
                if (!empty($readOnlyResourceArr)) {
                    foreach ($readOnlyResourceArr as $read) {
                        $readOnlyPermissionArr[] = $this->getPermissionTable()->getReadResourcePermission($read, $data['parent_role']);
                    }
                    $readOnlyPermissionArr[] = $this->getPermissionTable()->getFullResourcePermission('1', $data['parent_role']);
                }

                if (!empty($fullAccessResourceArr)) {
                    foreach ($fullAccessResourceArr as $full) {
                        $fullAccessPermissionArr[] = $this->getPermissionTable()->getFullResourcePermission($full, $data['parent_role']);
                    }
                    $fullAccessPermissionArr[] = $this->getPermissionTable()->getFullResourcePermission('1', $data['parent_role']);
                }
                if (!empty($disableResourceArr)) {
                    $disablePermissionArr = $this->getPermissionTable()->getFullResourcePermission('1', $data['parent_role']);
                }

                $readPermissionIdArr = $this->array_2d_to_1d($readOnlyPermissionArr);
                $fullPermissionIdArr = $this->array_2d_to_1d($fullAccessPermissionArr);
            }
//if ($form->isValid()) {
            if ($userRole->role_name != '' || $userRole->role_code != '') {
                $validatorRoleName = new \Zend\Validator\Db\NoRecordExists(
                        array(
                    'table' => 'role',
                    'field' => 'role_name',
                    'adapter' => $this->getAdapter(),
                    'exclude' => array(
                        'field' => 'rid',
                        'value' => $id,
                    )
                        )
                );

                $validatorRoleCode = new \Zend\Validator\Db\NoRecordExists(
                        array(
                    'table' => 'role',
                    'field' => 'role_code',
                    'adapter' => $this->getAdapter(),
                    'exclude' => array(
                        'field' => 'rid',
                        'value' => $id,
                    )
                        )
                );

                if ($validatorRoleName->isValid($userRole->role_name) && $validatorRoleCode->isValid($userRole->role_code)) {
                    $createdUserRoleId = $this->getRoleTable()->saveUserRole($userRole);
                    if (isset($createdUserRoleId) && $createdUserRoleId != '') {

                        // delete each permission for the role while updation role
                        $this->RolePermissionTable->deleteRolePermission($createdUserRoleId);

                        if (!empty($readPermissionIdArr)) {
                            foreach ($readPermissionIdArr as $readPermission) {
                                // save read only role's permission
                                $this->RolePermissionTable->saveRolePermission($createdUserRoleId, $readPermission['id']);
                            }
                        }

                        if (!empty($fullPermissionIdArr)) {
                            foreach ($fullPermissionIdArr as $fullPermission) {
                                // save full access role's permission
                                $this->RolePermissionTable->saveRolePermission($createdUserRoleId, $fullPermission['id']);
                            }
                        }

                        if (!empty($disablePermissionArr)) {
                            foreach ($disablePermissionArr as $disablePermission) {
                                // save disable role's permission
                                $this->RolePermissionTable->saveRolePermission($createdUserRoleId, $disablePermission['id']);
                            }
                        }
                        $this->getServiceLocator()->get('Zend\Log')->info('User Role with Id ' . $createdUserRoleId . ' added by user ' . $session->offsetGet('userId'));
                    }
                    if (isset($createdUserId) && $createdUserId != '') {
                        $this->getServiceLocator()->get('Zend\Log')->info('User Role with Id ' . $createdUserRoleId . ' added by user ' . $session->offsetGet('userId'));
                    }
                    $this->flashMessenger()->setNamespace('success')->addMessage('User Role Updated successfully');
                    return $this->redirect()->toRoute('userrole', array());
                } else {
                    $flashMessage = $this->flashMessenger()->getErrorMessages();
                    if (empty($flashMessage)) {
                        $this->flashMessenger()->setNamespace('error')->addMessage('User Role Code Already Exist'
                        );
                    }
                }
            }
//}
        }

        return array(
            'id' => $id,
            'form' => $form,
            'roleCode' => $roleCode,
            'resourceName' => $parentUniqueResourceNameArr,
            'onlyReadResource' => $onlyReadResource,
            'fullAccess' => $fullAccess,
            'readAccess' => $readAccess,
            'noAccess' => $outputNoAccess
        );
    }

    public function viewAction() {
        $uniqueResourceArr = array();
        $parentUniqueResourceNameArr = array();
        $parentUniqueResourceIdArr = array();
        $readResourceIdArr = array();
        $fullResourceIdArr = array();
        $disableResourceIdArr = array();

        $fullAccess = array();
        $readAccess = array();
        $noAccess = array();

        $onlyReadResource = array();
        $session = new Container('User');
        $id = (int) $this->params('id');
        if (!$id) {
            return $this->redirect()->toRoute('userrole', array('action' => 'index'));
        }

        $userRoleDetails = $this->getRoleTable()->getUserRole($id);
        $roleCode = $userRoleDetails->role_code;
        $roleResourcePermissionArr = $this->getRolePermissionTable()->getRoleResourcePermission($id);
        if (isset($userRoleDetails) && $userRoleDetails->parent_role_code == 0) {
            $userRoleDetails->parent_role_code = $userRoleDetails->rid; // if role is parent_role then assign its role_id to it
        }
        $parentRoleResourcePermissionArr = $this->getRolePermissionTable()->getRoleResourcePermission($userRoleDetails->parent_role_code);
        //get unique role's resource
        if (!empty($roleResourcePermissionArr)) {
            foreach ($roleResourcePermissionArr as $resourcePermission) {
                if (!in_array($resourcePermission['resource_id'], $uniqueResourceArr)) {
                    $uniqueResourceArr[] = $resourcePermission['resource_id'];
                    $details = explode('\\', $resourcePermission['resource_name']);
                    if ($details[2] != 'Index') {
                        $uniqueResourceNameArr[] = $details[2] . '*' . $resourcePermission['resource_id'];
                    }
                }
                if (preg_match('/^index*(\w+)/i', $resourcePermission['permission_name']) || preg_match('/^view(\w+)/i', $resourcePermission['permission_name']) || $resourcePermission['permission_name'] == 'view' || $resourcePermission['permission_name'] == 'index') {
                    $readResourceIdArr[] = $resourcePermission['resource_id'];
                } else if (preg_match('/^add*(\w+)/i', $resourcePermission['permission_name']) || preg_match('/^edit*(\w+)/i', $resourcePermission['permission_name']) || preg_match('/^create*(\w+)/i', $resourcePermission['permission_name']) || $resourcePermission['permission_name'] == 'add' || $resourcePermission['permission_name'] == 'edit') {
                    $fullResourceIdArr[] = $resourcePermission['resource_id'];
                } else {
                    $disableResourceIdArr[] = $resourcePermission['resource_id'];
                }
            }
        }


        //get unique parent role's resource
        if (!empty($parentRoleResourcePermissionArr)) {
            foreach ($parentRoleResourcePermissionArr as $parentResourcePermission) {
                if (!in_array($parentResourcePermission['resource_id'], $parentUniqueResourceIdArr)) {
                    $parentUniqueResourceIdArr[] = $parentResourcePermission['resource_id'];
                    $parentDetails = explode('\\', $parentResourcePermission['resource_name']);
                    if ($parentDetails[2] != 'Index') {
                        $parentUniqueResourceNameArr[] = $parentDetails[2] . '*' . $parentResourcePermission['resource_id'];
                    }
                }
            }
        }

        foreach ($parentUniqueResourceIdArr as $res) {
            $readResourceArr = $this->getPermissionTable()->getReadResourcePermission($res, $userRoleDetails->parent_role_code);
            $fullResourceArr = $this->getPermissionTable()->getFullResourcePermission($res, $userRoleDetails->parent_role_code);
            if ($readResourceArr === $fullResourceArr) {
                $onlyReadResource[] = $res;
            }
        }

        // classify resource on the basis of its accessibilty to the like fullAccess, readAccess and noAccess
        if (!empty($uniqueResourceArr)) {
            foreach ($uniqueResourceArr as $i) {
                if ((in_array($i, $readResourceIdArr) && in_array($i, $fullResourceIdArr)) || in_array($i, $disableResourceIdArr)) {
                    $fullAccess[] = $i;
                } else if ((in_array($i, $readResourceIdArr) && !in_array($i, $fullResourceIdArr)) || !in_array($i, $disableResourceIdArr)) {
                    $readAccess[] = $i;
                } else if (!in_array($i, $readResourceIdArr) && !in_array($i, $fullResourceIdArr) && in_array($i, $disableResourceIdArr)) {
                    $noAccess[] = $i;
                }
            }
        }
        // merge read & full access permission in to a single array
        $accessibleResourceArr = array_merge($fullAccess, $readAccess);
        // disable resources array
        $outputNoAccess = array_merge(array_diff($parentUniqueResourceIdArr, $accessibleResourceArr), array_diff($accessibleResourceArr, $parentUniqueResourceIdArr), $noAccess);
        return array(
            'roleDetails' => $userRoleDetails,
            'roleCode' => $roleCode,
            'resourceName' => $parentUniqueResourceNameArr,
            'onlyReadResource' => $onlyReadResource,
            'fullAccess' => $fullAccess,
            'readAccess' => $readAccess,
            'noAccess' => $outputNoAccess
        );
    }

}
