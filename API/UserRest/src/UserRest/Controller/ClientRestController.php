<?php

namespace UserRest\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Client as HttpClient;
use ZF2AuthAcl\Utility\UserPassword;

class ClientRestController extends AbstractActionController {

    protected $adapter;
    protected $ChapterTable;
    protected $QuestionTable;

    public function getChapterTable() {
        if (!$this->ChapterTable) {
            $sm = $this->getServiceLocator();
            $this->ChapterTable = $sm->get('Application\Model\ChapterTable');
        }
        return $this->ChapterTable;
    }

    public function getQuestionTable() {
        if (!$this->QuestionTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->QuestionTable = $sm->get('Questionarie\Model\QuestionTable');
        }
        return $this->QuestionTable;
    }

    public function indexAction() {
        $client = new HttpClient();
        $client->setAdapter('Zend\Http\Client\Adapter\Curl');

        $method = $this->params()->fromQuery('method', 'get');
        $client->setUri('http://localhost:80' . $this->getRequest()->getBaseUrl() . '/san-restful');
        switch ($method) {
            case 'get' :
                $client->setMethod('GET');
                $client->setParameterGET(array('id' => 1));
                break;
            case 'get-list' :
                $client->setMethod('GET');
                break;
            case 'create' :
                $client->setMethod('POST');
                $client->setParameterPOST(array('name' => 'samsonasik'));
                break;
            case 'update' :
                $data = array('name' => 'ikhsan');
                $adapter = $client->getAdapter();

                $adapter->connect('localhost', 80);
                $uri = $client->getUri() . '?id=1';
                // send with PUT Method, with $data parameter
                $adapter->write('PUT', new \Zend\Uri\Uri($uri), 1.1, array(), http_build_query($data));

                $responsecurl = $adapter->read();
                list($headers, $content) = explode("\r\n\r\n", $responsecurl, 2);
                $response = $this->getResponse();

                $response->getHeaders()->addHeaderLine('content-type', 'text/html; charset=utf-8');
                $response->setContent($content);

                return $response;
            case 'delete' :
                $adapter = $client->getAdapter();

                $adapter->connect('localhost', 80);
                $uri = $client->getUri() . '?id=1'; //send parameter id = 1
                // send with DELETE Method
                $adapter->write('DELETE', new \Zend\Uri\Uri($uri), 1.1, array());

                $responsecurl = $adapter->read();
                list($headers, $content) = explode("\r\n\r\n", $responsecurl, 2);
                $response = $this->getResponse();

                $response->getHeaders()->addHeaderLine('content-type', 'text/html; charset=utf-8');
                $response->setContent($content);
            case 'userlogin':
                $userDetails = $this->userlogin();
                $response = $this->getResponse();
                $response->getHeaders()->addHeaderLine('content-type', 'text/html; charset=utf-8');
                $response->setContent($userDetails);
                return $response;
            case 'demoquiz':
                $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
                $js_path = $renderer->basePath('js/koolguru/application');
                $headScript = $this->getServiceLocator()->get('viewhelpermanager')
                        ->get('headScript');

                $headScript->appendFile($js_path . '/demoquiz.js');
                $headScript->appendFile($js_path . '/exercise.js');
                $headScript->appendFile($js_path . '/quiz.js');
                $demochapter_id = 0;
                $demochapter = $this->getChapterTable()->getDemoChapter();
                $response = $this->getResponse();
                $response->getHeaders()->addHeaderLine('content-type', 'text/html; charset=utf-8');
                $response->setContent(json_encode($demochapter));
                return $response;
            case 'demoquizques':
                $demoQuiz = array();
                $demoQuiz = $this->getChapterTable()->getDemoQuiz();
                $questions = array();
                foreach ($demoQuiz as $data) {
                    $ques = $this->getQuestionTable()->getDemoExcerciseQuestions($data['quiz_id'], $data['level_id'], $data['category_id'], $data['ques_nos'], true);
                    $questions = array_merge($questions, $ques);
                }

                $demochapterquizque = array();
                if (!empty($questions)) {
                    foreach ($questions as $key => $v) {
                        $demochapterquizque[$v['que_id']] = $v;
                    }
                }
                $response = $this->getResponse();
                $response->getHeaders()->addHeaderLine('content-type', 'text/html; charset=utf-8');
                $response->setContent(json_encode($questions));
                return $response;
            case 'demoquizexer':
                $demoQuiz = array();
                $demoQuiz = $this->getChapterTable()->getDemoQuiz();
                $questions = $this->getQuestionTable()->getDemoExcerciseQuestions($demoQuiz[0]['quiz_id'], 3, $demoQuiz[0]['category_id'], 3, true);
                $questionFinal = array();
                foreach ($questions as $question) {
                    $questionFinal[] = $question;
                }
                $response = $this->getResponse();
                $response->getHeaders()->addHeaderLine('content-type', 'text/html; charset=utf-8');
                $response->setContent(json_encode($questionFinal));
                return $response;
        }

        //if get/get-list/create
        $response = $client->send();
        if (!$response->isSuccess()) {
            // report failure
            $message = $response->getStatusCode() . ': ' . $response->getReasonPhrase();

            $response = $this->getResponse();
            $response->setContent($message);
            return $response;
        }
        $body = $response->getBody();

        $response = $this->getResponse();
        $response->setContent($body);

        return $response;
    }

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
    public function userlogin() {
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
                    return json_encode($userDetails[0]);
                }
            } else {
                $userDetails[0]['code'] = 'ERR001';
                $userDetails[0]['error'] = 'Authentication is not successful';
                return json_encode($userDetails[0]);
            }
        } else {
            $userDetails[0]['code'] = 'ERR002';
            $userDetails[0]['error'] = 'Please provide valid credentials';
            return json_encode($userDetails[0]);
        }
    }

    private function _getStudentDetails($where, $columns) {

        $userTable = $this->getServiceLocator()->get("UserTable");
        $users = $userTable->getStudent($where, $columns);
        return $users;
    }

}
