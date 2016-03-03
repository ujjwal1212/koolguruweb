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

    public function getSendqueryTable() {
        if (!$this->SendqueryTable) {
            $sm = $this->getServiceLocator();
            $this->SendqueryTable = $sm->get('Application\Model\SendqueryTable');
        }
        return $this->SendqueryTable;
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
        $teams = $this->getTeamTable()->getTeam();
        
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
        $cond = array();
        $cond['level'] = 2;
        $cond['status'] = 1;
        $questions = array();
        $questions = $this->getQuestionTable()->getExcerciseQuestions($cond);
        $excecises = array();
        $que = array();
        foreach ($questions as $dat) {
            if (empty($que)) {
                $excecises[$dat['id']] = array('title' => $dat['description'], 'min_marks' => $dat['min_marks'], 'max_marks' => $dat['max_marks']);
                $que[] = $dat['id'];
            } else {
                if (!in_array($dat['id'], $que)) {
                    $excecises[$dat['id']] = array('title' => $dat['description'], 'min_marks' => $dat['min_marks'], 'max_marks' => $dat['max_marks']);
                    $que[] = $dat['id'];
                }
            }
        }

        $ques = '';
        foreach ($questions as $dat) {
            if ($ques != $dat['id']) {
                $ques = $dat['id'];
            }

            if ($ques == $dat['id']) {
                $excecises[$dat['id']]['options'][$dat['option_id']] = array('description' => $dat['option_description'], 'iscorrect' => $dat['is_correct']);
            }
        }
        $response->setContent(json_encode($excecises));
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
        $cond = array();
        $cond['level'] = 2;
        $cond['status'] = 1;
        $questions = array();
        $questions = $this->getQuestionTable()->getExcerciseQuestions($cond);
        $excecises = array();
        $que = array();
        foreach ($questions as $dat) {
            if (empty($que)) {
                $excecises[$dat['id']] = array('title' => $dat['description'], 'min_marks' => $dat['min_marks'], 'max_marks' => $dat['max_marks']);
                $que[] = $dat['id'];
            } else {
                if (!in_array($dat['id'], $que)) {
                    $excecises[$dat['id']] = array('title' => $dat['description'], 'min_marks' => $dat['min_marks'], 'max_marks' => $dat['max_marks']);
                    $que[] = $dat['id'];
                }
            }
        }

        $ques = '';
        foreach ($questions as $dat) {
            if ($ques != $dat['id']) {
                $ques = $dat['id'];
            }

            if ($ques == $dat['id']) {
                $excecises[$dat['id']]['options'][$dat['option_id']] = array('description' => $dat['option_description'], 'iscorrect' => $dat['is_correct']);
            }
        }
        $response->setContent(json_encode($excecises));
        return $response;
    }

}
