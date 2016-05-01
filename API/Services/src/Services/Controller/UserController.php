<?php

namespace Services\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Db\Sql\Select;
use ZF2AuthAcl\Utility\UserPassword;

class UserController extends AbstractActionController {

    protected $adapter;

    public function getAdapter() {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }

    /**
     * Service to authenticate the user
     */
    public function userloginAction() {
        $this->layout('layout/ajax');
        $postParams = $_POST;
        $email = isset($postParams['email']) && $postParams['email'] != '' ? $postParams['email'] : '';
        $password = isset($postParams['password']) && $postParams['password'] != '' ? $postParams['password'] : '';
        if ($email != '' && $password != '') {
            $userPassword = new UserPassword();
            $encyptPass = $userPassword->create($password);
            $authService = $this->getServiceLocator()->get('StudentAuthService');
            $authService->getAdapter()
                    ->setIdentity($email)
                    ->setCredential($encyptPass);

            $result = $authService->authenticate();
            if ($result->isValid()) {
                $userDetails = $this->_getStudentDetails(array(
                    'student.email' => $email
                        ), array(
                    'id', 'status', 'fname', 'mname', 'lname', 'isprofilecompleted', 'email'
                ));
                $userDetails[0]['code'] = 200;
                if ($userDetails[0]['status'] == 1) {
                    echo json_encode($userDetails[0]);
                    die;
                }
            } else {
                $userDetails[0]['code'] = 'ERR001';
                $userDetails[0]['error'] = 'Authentication is not successful';
                echo json_encode($userDetails[0]);
                die;
            }
        } else {
            $userDetails[0]['code'] = 'ERR002';
            $userDetails[0]['error'] = 'Please provide valid credentials';
            echo json_encode($userDetails[0]);
            die;
        }
    }

    private function _getStudentDetails($where, $columns) {

        $userTable = $this->getServiceLocator()->get("UserTable");
        $users = $userTable->getStudent($where, $columns);
        return $users;
    }

}
