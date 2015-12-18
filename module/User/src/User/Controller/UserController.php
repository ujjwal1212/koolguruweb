<?php

/*
 * Controller Class for User module
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Form\UserForm;
use Center\Form\CenterForm;
use Center\Form\CenterGroupForm;
use ExternalVerifier\Form\ExternalVerifierForm;
use ExternalVerifier\Model\ExternalVerifier;
use Center\Model\Center;
use Center\Model\CenterGroup;
use User\Form\Filter\UserFilter;
use User\Form\SearchForm;
use Zend\Db\Sql\Select;
use ZF2AuthAcl\Model\UserTable;
use ZF2AuthAcl\Model\UserRoleTable;
use Zend\Authentication\Adapter\DbTable;
use Zend\Session\Container;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use ZF2AuthAcl\Model\RecoverEmail;
use ZF2AuthAcl\Model\RecoverEmailTable;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Expression;
use User\Form\ResetPasswordForm;
use ZF2AuthAcl\Utility\UserPassword;

class UserController extends AbstractActionController {

    protected $UserTable;
    protected $centerTable;
    protected $qualificationTable;
    protected $centerGroupTable;
    protected $regionTable;
    protected $cityTable;
    protected $adapter;
    protected $RecoverEmailTable;
    protected $UserRoleTable;
    protected $RoleTable;

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
     * Get Instance RecoverEmailTable class
     * @return type
     */
    public function getRecoverEmailTable() {
        if (!$this->RecoverEmailTable) {
            $sm = $this->getServiceLocator();
            $this->RecoverEmailTable = $sm->get('ZF2AuthAcl\Model\RecoverEmailTable');
        }
        return $this->RecoverEmailTable;
    }

    public function getCenterTable() {
        if (!$this->centerTable) {
            $sm = $this->getServiceLocator();
            $this->centerTable = $sm->get('Center\Model\CenterTable');
        }
        return $this->centerTable;
    }

    public function getQualificationTable() {
        if (!$this->qualificationTable) {
            $sm = $this->getServiceLocator();
            $this->qualificationTable = $sm->get('Qualification\Model\QualificationTable');
        }
        return $this->qualificationTable;
    }

    public function getCenterGroupTable() {
        if (!$this->centerGroupTable) {
            $sm = $this->getServiceLocator();
            $this->centerGroupTable = $sm->get('Center\Model\CenterGroupTable');
        }
        return $this->centerGroupTable;
    }

    public function getRegionTable() {
        if (!$this->regionTable) {
            $sm = $this->getServiceLocator();
            $this->regionTable = $sm->get('Trainee\Model\RegionTable');
        }
        return $this->regionTable;
    }

    public function getCityTable() {
        if (!$this->cityTable) {
            $sm = $this->getServiceLocator();
            $this->cityTable = $sm->get('Trainee\Model\CityTable');
        }
        return $this->cityTable;
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
     * Function to get instance of role table
     * @return type
     */
    public function getUserRoleTable() {
        if (!$this->UserRoleTable) {
            $sm = $this->getServiceLocator();
            $this->UserRoleTable = $sm->get('ZF2AuthAcl\Model\UserRoleTable');
        }
        return $this->UserRoleTable;
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
            $order_by = isset($data['order_by']) && trim($data['order_by']) != '' ? $data['order_by'] : 'id';
            $order = isset($data['order']) && trim($data['order']) != '' ? $data['order'] : Select::ORDER_DESCENDING;
            $searchText = isset($data['search_box_value']) ? trim($data['search_box_value']) : Null;
            $form->get('list_count')->setValue($list_count);
            $form->get('order_by')->setValue($order_by);
            $form->get('order')->setValue($order);
            $form->setData($data);
            if ($form->isValid()) {
                $paginator = $this->getUserTable()->fetchAll(true, $order_by, $order, $searchText);
            }
        } else {
            // grab the paginator from the UnitTable
            $paginator = $this->getUserTable()->fetchAll(true, $order_by, $order, $searchText);
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
    /**
     * Function to add new user
     */
    public function addAction() {
        $session = new Container('User');
        $roleCode = $session->offsetGet('roleCode');
        $userRoles = $this->getUserTable()->userRole($roleCode);
        $request = $this->getRequest();
        $form = new UserForm('userForm', $userRoles);
        $form->setInputFilter(new UserFilter());
        $form->get('status')->setAttributes(array(
            'disabled' => 'disabled',
        ));
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid() || 1) {
                $email = $data['email'];
                $national_id = $data['national_id'];
                if ($email != '') {
                    $validator = new \Zend\Validator\EmailAddress();
                    if ($validator->isValid($email)) {
                        // email appears to be valid
                        // check existence of email in UserTable
                        $validatorEmail = new \Zend\Validator\Db\NoRecordExists(
                                array(
                            'table' => 'users',
                            'field' => 'email',
                            'adapter' => $this->getAdapter()
                                )
                        );
                        $validatorId = new \Zend\Validator\Db\NoRecordExists(
                                array(
                            'table' => 'users',
                            'field' => 'national_id',
                            'adapter' => $this->getAdapter()
                                )
                        );
                        if (isset($national_id) && $national_id !== '' && $validatorId->isValid($national_id) && $validatorEmail->isValid($email)) {
                            $flag = 1;
                        } else if ($national_id == '' && $validatorEmail->isValid($email)) {
                            //die('here');
                            $flag = 1;
                        } else {
                            $flag = 0;
                        }
                        if ($flag == 1) {
                            $session = new Container('User');
                            $data['created_by'] = $session->offsetGet('userId');
                            $data['status'] = 0;
                            $createdUserId = $this->getUserTable()->saveUser($data);
                            if (isset($createdUserId) && $createdUserId != '') {
                                $this->getServiceLocator()->get('Zend\Log')->info('User with Id ' . $createdUserId . ' added by user ' . $session->offsetGet('userId'));
                            }
                            $hashValueReturn = $this->getUserTable()->saveActivationEmail($email);
                            $this->sendActivationLink($email, $hashValueReturn);
                            $this->flashMessenger()->setNamespace('success')->addMessage('User created successfully & Mail Sent Successfully to the user');
                            return $this->redirect()->toRoute('user', array());
                        } else {
                            $flashMessage = $this->flashMessenger()->getErrorMessages();
                            if (empty($flashMessage)) {
                                $this->flashMessenger()->setNamespace('error')->addMessage('User National Id or Email Id Already Exist'
                                );
                            }
                        }
                    }
                }
            }
        }
        return array('form' => $form);
    }

    /**
     * Function to edit a user
     * @return type
     */
    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $msg = (int) $this->params()->fromRoute('msg', 0);
        if (!$id) {
            return $this->redirect()->toRoute('user', array('action' => 'index'));
        }
        $session = new Container('User');
        $user = $this->getUserTable()->getUser($id);
        $existingEmailId = $user->email;
        $archive_email_ids = $user->archive_email_ids;
        if ($user['national_id'] == '-1') {
            $user['national_id'] = '';
        }
        if ($user['age'] == '1') {
            $user['age'] = '';
        }
        $roleCode = $session->offsetGet('roleCode');
        $parentRoleDetails = $this->getRoleTable()->getUserRole($user->role_id);
        if (isset($parentRoleDetails) && $parentRoleDetails->parent_role_code != '') {
            $parentRoleId = $parentRoleDetails->parent_role_code;
        }
        if ($parentRoleId != 0) {
            $userRoles = $this->getUserTable()->getAllRelativeRole($user->role_id, $parentRoleId);
        } else {
            $userRoles = $this->getUserTable()->getChildRole($user->role_id);
        }

        $form = new UserForm('userForm', $userRoles);
        //$form->get('national_id')->setAttribute('readonly', true);

        if (isset($msg) && $msg == 1) {
            $email = $user->email;
            //function to delete row from activation with $user->email
            $this->getRecoverEmailTable()->deleteActivationEmail($email);
            $hashValueReturn = $this->getUserTable()->saveActivationEmail($email);
            $this->sendActivationLink($email, $hashValueReturn);
            $flashMessage = $this->flashMessenger()->getCurrentSuccessMessages();
            if (empty($flashMessage)) {
                $this->flashMessenger()->setNamespace('success')->addMessage('Activation Email has been sent successfully');
            }
            return $this->redirect()->toRoute('user');
        }
        $notActiveUser = 0;
//        if (isset($user) && $user != '') {
//            $userStatus = $user->status;
//        }
        $user->role = $user->role_id;
        if (empty($user->password)) {
            $notActiveUser = 1;
            $form->get('status')->setAttributes(array(
                'disabled' => 'disabled',
            ));
        }
        $form->bind($user);
        $form->setInputFilter(new UserFilter());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $data = $request->getPost();
            if ($form->isValid() || 1) {
                $email = $data['email'];
                $national_id = $data['national_id'];
                if ($email != '') {
                    $validator = new \Zend\Validator\EmailAddress();
                    if ($validator->isValid($email)) {
                    // email appears to be valid
                    // check existence of email in UserTable
                        $validatorEmail = new \Zend\Validator\Db\NoRecordExists(
                                array(
                            'table' => 'users',
                            'field' => 'email',
                            'adapter' => $this->getAdapter(),
                            'exclude' => array(
                                'field' => 'user_id',
                                'value' => $id,
                            )
                                )
                        );
                    // check existence of national id in UserTable
                        $validatorId = new \Zend\Validator\Db\NoRecordExists(
                                array(
                            'table' => 'users',
                            'field' => 'national_id',
                            'adapter' => $this->getAdapter(),
                            'exclude' => array(
                                'field' => 'user_id',
                                'value' => $id,
                            )
                                )
                        );
//                        if ($validatorEmail->isValid($email) && $validatorId->isValid($national_id)) {
//                            $flag = 1;
//                        } else 
                        if (isset($national_id) && $national_id !== '' && $validatorId->isValid($national_id) && $validatorEmail->isValid($email)) {
                            $flag = 1;
                        } else if ($national_id == '' && $validatorEmail->isValid($email)) {
                            //die('here');
                            $flag = 1;
                        }
//                        else if ($validatorEmail->isValid($email)) {
//                             die('dea');
//                            $flag = 1;
//                        } 
                        else {
                            $flag = 0;
                        }
                        if ($flag == 1) {
                            $session = new Container('User');
                            $data['created_by'] = $session->offsetGet('userId');
                            $role_id = $data['role'];
                            $userId = $this->getUserTable()->updateUser($data, $id, $parentRoleDetails->role_code);
                            if (isset($userId) && $userId != '') {
                                $roleId = $this->getUserRoleTable()->updateUserRole($userId, $role_id, $data['created_by']);
                                if($existingEmailId != $email){
                                    // 1. Add old archive email ids
                                    if($archive_email_ids != ''){
                                        $archive = $existingEmailId.','.$archive_email_ids;
                                    }else{
                                        $archive = $existingEmailId;
                                    }
                                    $update = $this->UserTable->updateArchiveMails($archive, $userId);
                                    //echo $update; die;
                                    if($update){
                                        // 2. Send Email for Reset Password
//                                        // check email address is active
                                        $validEmail = $this->getUserTable()->checkActiveEmail($email);
                                        if ($validEmail[0]['status'] == 0) {
                                            $errNo = 5;
                                        } else if ($validEmail[0]['status'] == 1) {
                                            $errNo = 4; // Email in DB. send email link to user. 
                                            // delete the record of email from iyc_recover 
                                            $this->getRecoverEmailTable()->deleteRecoverEmail($email);
                                            // add new record
                                            $hashValueReturn = $this->getRecoverEmailTable()->addRecoverEmail($email);
                                            // send link to recover password
                                            $this->sendRecoverEmail($hashValueReturn, $email);
                                        } else {
                                            $errNo = 3;
                                        }
                                    }
                                }
                                $this->getServiceLocator()->get('Zend\Log')->info('User with Id ' . $userId . ' updated by user ' . $session->offsetGet('userId'));
                            }
                            $this->flashMessenger()->setNamespace('success')->addMessage('User Updated successfully');
                            return $this->redirect()->toRoute('user', array());
                        } else {
                            $flashMessage = $this->flashMessenger()->getErrorMessages();
                            if (empty($flashMessage)) {
                                $this->flashMessenger()->setNamespace('error')->addMessage('User NationalId or EmailId Already Exist'
                                );
                            }
                        }
                    }
                }
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
            'userStatus' => $notActiveUser
        );
    }

    public function editprofileAction() {
        $session = new Container('User');
        $userId = $session->offsetGet('userId');
        if (!$userId) {
            return $this->redirect()->toRoute('login', array());
        }
        $roleCode = $session->offsetGet('roleCode');
        $userRoles = $this->getUserTable()->userRole($roleCode);
        $form = new UserForm('userForm', $userRoles);
        $form->get('national_id')->setAttribute('readonly', true);
        $form->get('email')->setAttribute('readonly', true);
        $form->get('submit')->setValue('Update');
        $user = $this->getUserTable()->getUser($userId);
        $user->role = $user->role_id;
        if (empty($user->password)) {
            $form->get('status')->setAttributes(array(
                'disabled' => 'disabled',
            ));
        }
        $form->bind($user);
        $form->setInputFilter(new UserFilter());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $data = $request->getPost();
            if ($form->isValid() || 1) {
                $email = $data['email'];
                $national_id = $data['national_id'];
                if ($email != '') {
                    $validator = new \Zend\Validator\EmailAddress();
                    if ($validator->isValid($email)) {
// email appears to be valid
// check existence of email in UserTable
                        $validatorEmail = new \Zend\Validator\Db\NoRecordExists(
                                array(
                            'table' => 'users',
                            'field' => 'email',
                            'adapter' => $this->getAdapter(),
                            'exclude' => array(
                                'field' => 'user_id',
                                'value' => $userId,
                            )
                                )
                        );
// check existence of national id in UserTable
                        $validatorId = new \Zend\Validator\Db\NoRecordExists(
                                array(
                            'table' => 'users',
                            'field' => 'national_id',
                            'adapter' => $this->getAdapter(),
                            'exclude' => array(
                                'field' => 'national_id',
                                'value' => $national_id,
                            )
                                )
                        );

                        if ($validatorEmail->isValid($email) && $validatorId->isValid($national_id)) {
                            $session = new Container('User');
                            $data['created_by'] = $session->offsetGet('userId');
                            $data['status'] = 1;
                            $updatedUserId = $this->getUserTable()->updateUser($data, $userId);
                            /**
                             * update session variables for  user name 
                             * START
                             */
                            $session->offsetSet('fname', $data['fname']);
                            $session->offsetSet('lname', $data['lname']);
                            /**
                             * END
                             */
                            if (isset($updatedUserId) && $updatedUserId != '') {
                                $this->getServiceLocator()->get('Zend\Log')->info('User with Id ' . $updatedUserId . ' updated by user ' . $session->offsetGet('userId'));
                            }
                            $this->flashMessenger()->setNamespace('success')->addMessage('Profile Updated successfully');
// Redirect to list of users
                            return $this->redirect()->toRoute('user', array('action' => 'viewprofile'));
                        } else {
                            $this->flashMessenger()->addMessage(array(
                                'error' => 'Duplicate User'
                            ));
                        }
                    }
                }
            }
        }

        return array(
            'id' => $userId,
            'form' => $form,
        );
    }

    /**
     * function for user view
     * @return type
     */
    public function viewprofileAction() {
        $session = new Container('User');
        $roleCode = $session->offsetGet('roleCode');
        $userId = $session->offsetGet('userId');
        if (!$userId) {
            return $this->redirect()->toRoute('login', array());
        }
        $userRoles = $this->getUserTable()->userRole($roleCode);
        $user = $this->getUserTable()->getUser($userId);
        $region = NULL;
        if (isset($user->region) && $user->region != '') {
            $region = $this->getRegionTable()->getRegion($user->region);
            $region = $region->name;
        }

        $city = NULL;
        if (isset($user->city) && $user->city != '') {
            $cityId = $this->getCityTable()->getCityId($user->city);
            $city = $cityId->name;
        }
        $user->role = $user->role_id;
        return array(
            'user' => $user,
            'region' => $region,
            'city' => $city
        );
    }
    /**
     * Function to send a activation link to user
     * @param type $email
     */
    public function sendActivationLink($email, $hashValueReturn) {
        $session = new Container('User');
        $adminEmail = $session->offsetGet('email');
        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        $requesturi = $this->getRequest()->getUri();
        $this->baseUrl = $requesturi->getHost() . $renderer->basePath();
        $url = $requesturi->getScheme() . '://' . $this->baseUrl . '/login/activate/' . $hashValueReturn;
//subject of the email
        $subject = 'Activate Account';
//body of the email
        $reciever_message = "Dear User $email\n\n 
                             This mail is a system generated email to activate your account...\n\n";
        $reciever_message .= "Please visit $url to activate your account and please set your password.\n\n";
        $reciever_message .= "Note:-\n 1.This link will automatically expire after 72 hours.\n 2.In case your link is expired please write to $adminEmail.\n 3.Please do not reply to this email.";
        $this->getRecoverEmailTable()->sendEmailToUser($subject, $reciever_message, $email);
    }

    /**
     * 
     */
    public function resetpasswordAction() {
        $authService = $this->getServiceLocator()->get('AuthService');
        $form = new ResetPasswordForm('resetPassword');
        $request = $this->getRequest();
        $recoverObj = new RecoverEmail();
        $session = new Container('User');
        $userId = $session->offsetGet('userId');
        $userData = $this->getUserTable()->getUser($userId);
        $errorMessage = array();
        if ($request->isPost()) {
            $form->setInputFilter($recoverObj->getInputFilter());
            $form->setData($request->getPost());
            $data = $request->getPost();
            $userPassword = new UserPassword();
            $oldencyptPass = $userPassword->create($data['oldpassword']);
//echo $userData->password ."==". $oldencyptPass; die;
            if ($userData->password == $oldencyptPass) {
//if ($form->isValid()) {
                $email = $session->offsetGet('email');
                $this->getUserTable()->updateRecoveryPassword($email, $data['password']);
                $session->getManager()->destroy();
                $authService->clearIdentity();
                $this->flashMessenger()->addMessage(array(
                    'error' => 'Password updated successfully, please login using new password'
                ));
                return $this->redirect()->toUrl('../login/index/updated');
//return $this->redirect()->toRoute('login', array('action' => 'index/updated'));
//}
            } else {
//                $this->flashMessenger()->addMessage(array(
//                    'error' => 'Old password incorrect'
//                ));
//$errorMessage = array('error' => 'Old password incorrect');
                $this->flashMessenger()->setNamespace('error')->addMessage('Old password incorrect');
                return $this->redirect()->toRoute('user', array(
                            'action' => 'resetpassword'
                ));
            }
        }
        return array(
            'form' => $form,
            //'flashMessages' => $this->flashMessenger()->getMessages()
            'errorMessage' => $errorMessage,
        );
    }
    
    /**
     * Function to send recove link to user to update password
     * @param type $hashValueReturn
     * @param type $email
     */
    public function sendRecoverEmail($hashValueReturn, $email) {

        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        $ref_file_name = explode('user', $_SERVER['HTTP_REFERER']);
        //asd($ref_file_name);
        $requesturi = $this->getRequest()->getUri();
        $this->baseUrl = $requesturi->getHost() . $renderer->basePath();
        $url = $ref_file_name[0] . 'login/recoverpassword/' . $hashValueReturn;
        $emailData = array('email' => $email, 'url' => $url);
        $content = $renderer->render('zf2-auth-acl/index/tpl/recovertpl', array('emailData' => $emailData));
        //subject of the email
        $subject = 'Reset password';
        //body of the email
        //$reciever_message = "User $email\n\n 
        //                     You have sent us a request for your password reset...\n\n";
        //$reciever_message .= "Please go to $url to reset your password";
        $this->getRecoverEmailTable()->sendEmailToUser($subject, $content, $email);
    }
    
}
