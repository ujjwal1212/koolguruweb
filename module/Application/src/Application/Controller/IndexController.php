<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Application\Model\Sendquery;
use Application\Model\SendqueryTable;
use Application\Model\Chapter;
use Application\Model\ChapterTable;
use Application\Model\Subject;
use Application\Model\SubjectTable;
use Student\Model\CarrierQuestion;
use Student\Model\CarrierQuestionTable;

class IndexController extends AbstractActionController {

    protected $adapter;
    protected $SendqueryTable;
    protected $ChapterTable;
    protected $QuestionTable;
    protected $SubjectTable;
    protected $TestimonialTable;
    protected $TeamTable;
    protected $packageTable;
    protected $OrderTable;
    protected $RecoverEmailTable;

    public function getAdapter() {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }

    public function getQuestionTable() {
        if (!$this->QuestionTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->QuestionTable = $sm->get('Questionarie\Model\QuestionTable');
        }
        return $this->QuestionTable;
    }

    public function getPackageTable() {
        if (!$this->packageTable) {
            $sm = $this->getServiceLocator();
            $this->packageTable = $sm->get('Package\Model\PackageTable');
        }
        return $this->packageTable;
    }

    public function getRecoverEmailTable() {
        if (!$this->RecoverEmailTable) {
            $sm = $this->getServiceLocator();
            $this->RecoverEmailTable = $sm->get('ZF2AuthAcl\Model\RecoverEmailTable');
        }
        return $this->RecoverEmailTable;
    }

    public function getSendqueryTable() {
        if (!$this->SendqueryTable) {
            $sm = $this->getServiceLocator();
            $this->SendqueryTable = $sm->get('Application\Model\SendqueryTable');
        }
        return $this->SendqueryTable;
    }

    public function getOrderTable() {
        if (!$this->OrderTable) {
            $sm = $this->getServiceLocator();
            $this->OrderTable = $sm->get('Application\Model\OrderTable');
        }
        return $this->OrderTable;
    }

    public function getChapterTable() {
        if (!$this->ChapterTable) {
            $sm = $this->getServiceLocator();
            $this->ChapterTable = $sm->get('Application\Model\ChapterTable');
        }
        return $this->ChapterTable;
    }

    public function getSubjectTable() {
        if (!$this->SubjectTable) {
            $sm = $this->getServiceLocator();
            $this->SubjectTable = $sm->get('Application\Model\SubjectTable');
        }
        return $this->SubjectTable;
    }

    public function getTestimonialTable() {
        if (!$this->TestimonialTable) {
            $sm = $this->getServiceLocator();
            $this->TestimonialTable = $sm->get('Application\Model\TestimonialTable');
        }
        return $this->TestimonialTable;
    }

    public function getTeamTable() {
        if (!$this->TeamTable) {
            $sm = $this->getServiceLocator();
            $this->TeamTable = $sm->get('Application\Model\TeamTable');
        }
        return $this->TeamTable;
    }

    public function indexAction() {
        $testimonials = array();
        $testimonials = $this->getTestimonialTable()->getTestimonial();
        $packages = $this->getPackageTable()->getPackages();
        $teams = $this->getTeamTable()->getTeam(0, true);

        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        $js_path = $renderer->basePath('js/koolguru/application');
        $headScript = $this->getServiceLocator()->get('viewhelpermanager')
                ->get('headScript');

        $headScript->appendFile($js_path . '/demovideos.js');
        $session = new Container('User');
        if ($session->offsetExists('email') && $session->offsetGet('roleCode') == 'st') {
            $this->redirect()->toRoute('student');
        }

        if ($session->offsetExists('email') && $session->offsetGet('roleCode') == 'sa') {
            $this->redirect()->toRoute('admin');
        }
        return array(
            'testimonials' => $testimonials,
            'teams' => $teams,
            'packages' => $packages
        );
    }

    /**
     * function for Package view
     * @return type
     */
    public function packagedetailsAction() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        if (!$id) {
            return $this->redirect()->toRoute('home');
        }
        $package = $this->getPackageTable()->getPackageDetails($id);
        return array(
            'package' => $package,
            'id' => $id,
        );
    }

    public function locateusAction() {
        
    }

    public function aboutusAction() {
        
    }

    public function visionAction() {
        
    }

    public function missionAction() {
        
    }

    public function faqAction() {
        
    }

    public function privacyAction() {
        
    }

    public function testimonialAction() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $testimonials = array();
        $testimonials = $this->getTestimonialTable()->getTestimonial($id);
        return array(
            'testimonials' => $testimonials,
        );
    }

    public function teamAction() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $teams = array();
        $teams = $this->getTeamTable()->getTeam($id);
        return array(
            'teams' => $teams,
        );
    }

    public function contactusAction() {
        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        $js_path = $renderer->basePath('js/koolguru/application');
        $headScript = $this->getServiceLocator()->get('viewhelpermanager')
                ->get('headScript');

        $headScript->appendFile($js_path . '/contact_us.js');
        $errorMsg = '';
        $successMsg = '';
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getSendqueryTable()->saveQuery($data);
            $errorMsg = $this->flashMessenger()->getCurrentMessagesFromNamespace('error');
            $successMsg = $this->flashMessenger()->getCurrentMessagesFromNamespace('success');
            //$successMsg = 'Thanks you for the submission, We will get back to you soon!';
            $this->flashMessenger()->setNamespace('success')->addMessage('Thanks you for the submission, We will get back to you soon!');
        }
        return new ViewModel(array(
            'errorMsg' => $errorMsg,
            'successMsg' => $successMsg
        ));
    }

    public function demoquizAction() {
        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        $js_path = $renderer->basePath('js/koolguru/application');
        $headScript = $this->getServiceLocator()->get('viewhelpermanager')
                ->get('headScript');

        $headScript->appendFile($js_path . '/demoquiz.js');
        $headScript->appendFile($js_path . '/exercise.js');
        $headScript->appendFile($js_path . '/quiz.js');
        $demochapter_id = 0;
        $demochapter = $this->getChapterTable()->getDemoChapter();
        //asd($demochapter);
        return array(
            'demochapter' => $demochapter,
        );
    }

    public function exerciseAction() {

        $viewModel = new ViewModel(array(
        ));
        $viewModel->setTerminal(true);
        $this->layout('layout/empty');
        $request = $this->getRequest();
        $data = $request->getPost();
        $response = $this->getResponse();

        $demoQuiz = array();
        $demoQuiz = $this->getChapterTable()->getDemoQuiz();
        $questions = $this->getQuestionTable()->getDemoExcerciseQuestions($demoQuiz[0]['quiz_id'], 3, $demoQuiz[0]['category_id'], 3);

        $response->setContent(json_encode($questions));
        return $response;
    }

    public function quizAction() {

        $viewModel = new ViewModel(array(
        ));
        $viewModel->setTerminal(true);
        $this->layout('layout/empty');
        $request = $this->getRequest();
        $data = $request->getPost();
        $response = $this->getResponse();

        $demoQuiz = array();
        $demoQuiz = $this->getChapterTable()->getDemoQuiz();
        $questions = array();
        foreach ($demoQuiz as $data) {
            $ques = $this->getQuestionTable()->getDemoExcerciseQuestions($data['quiz_id'], $data['level_id'], $data['category_id'], $data['ques_nos']);
            $questions = array_merge($questions, $ques);
        }

        $demochapterquizque = array();
        if (!empty($questions)) {
            foreach ($questions as $key => $v) {
                $demochapterquizque[$v['que_id']] = $v;
            }
        }
        $response->setContent(json_encode($demochapterquizque));
        return $response;
    }

    public function initiatetransactionAction() {
        $session = new Container('User');
        $userId = '';
        $userId = $session->offsetGet('userId');
        $viewModel = new ViewModel(array(
        ));
        $package_id = isset($_GET['id']) ? $_GET['id'] : '';
        $viewModel->setTerminal(true);
        $this->layout('layout/empty');
        $orderId = $this->getOrderTable()->saveTransaction($package_id, $userId);
        $response = $this->getResponse();
        $response->setContent($orderId);
        return $response;
    }

    public function userpayAction() {

        $session = new Container('User');
        $userData = array();
        $userData['firstname'] = $session->offsetGet('fname');
        $userData['email'] = $session->offsetGet('email');
        $userData['phone'] = $session->offsetGet('mobile');
        $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);

        $hash = '';
        $userId = $session->offsetGet('userId');
        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        $js_path = $renderer->basePath('js/koolguru/application');
        $headScript = $this->getServiceLocator()->get('viewhelpermanager')
                ->get('headScript');
        $config = $this->getServiceLocator()->get('Config');
//        $config['payu_config']
        $headScript->appendFile($js_path . '/contact_us.js');
        $errorMsg = '';
        $successMsg = '';
        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        if (!$id) {
            return $this->redirect()->toRoute('home');
        }

        $package = $this->getPackageTable()->getPackageDetails($id);
        $request = $this->getRequest();
        $hashSequence = $config['payu_config']['merchant_key'] . '|' . $txnid . '|' . (int) $package[0]['price'] . '|' . $package[0]['title'] . '|' . $userData['firstname'] . '|' . $userData['email'];
        $hashVarsSeq = explode('|', $hashSequence);
        $hash_string = '';
        foreach ($hashVarsSeq as $hash_var) {
            $hash_string .= isset($hash_var) ? $hash_var : '';
            $hash_string .= '|';
        }
        $hash_string .= '||||||||||';
        $hash_string .= $config['payu_config']['merchant_salt'];
        //echo $hash_string;
        //die;
        $hash = strtolower(hash('sha512', $hash_string));

        $action = $config['payu_config']['payu_base_url'] . '/_payment';
        return new ViewModel(array(
            'errorMsg' => $errorMsg,
            'successMsg' => $successMsg,
            'packages' => $package,
            'userData' => $userData,
            'config' => $config['payu_config'],
            'action' => $action,
            'hash' => $hash,
            'txnid' => $txnid
        ));
    }

    public function payresponseAction() {
        $request = $this->getRequest();
        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        $session = new Container('User');
        $userId = $session->offsetGet('userId');
        $data = $request->getPost();
        $success = 0;
        $failure = 0;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        if ($data->status == 'success') {
            $flag = $this->getOrderTable()->updateTransaction($id, $userId);
            $orderDetails = $this->getOrderTable()->getOrderDetails($id, $userId);
            $package = $this->getPackageTable()->getPackageDetails($id);
            if ($orderDetails[0]['status']) {
                $success = 1;
                $subscriptionId = $this->getOrderTable()->saveSubscription($id, $userId, $orderDetails[0]['id'], $package);
                $subject = 'Oder Id : ' . $data['txnid'];
                $emailData = array('email' => $data->firstname, 'package' => $package[0]['title']);
                $content = $renderer->render('zf2-auth-acl/index/tpl/subscription', array('emailData' => $emailData));
                $this->getRecoverEmailTable()->sendEmailToUser($subject, $content, $data->email);
            } else {
                $failure = 1;
            }
        } else {
            $failure = 1;
        }
        return new ViewModel(array(
            'success' => $success,
            'failure' => $failure,
            'txnid' => $data->txnid,
        ));
    }

}
