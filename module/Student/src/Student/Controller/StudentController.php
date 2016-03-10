<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Student\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Student\Form\StudentForm;
use Student\Model\Degree;
use Student\Model\DegreeTable;
use Student\Model\State;
use Student\Model\StateTable;
use Student\Model\Student;
use Student\Model\StudentTable;
use Zend\Session\Container;
use Student\Form\Filter\StudentFilter;
use Student\Model\StudentVerbal;
use Student\Model\StudentVerbalTable;
use Student\Model\StudentStatus;
use Student\Model\StudentStatusTable;
use Student\Model\StudentQuants;
use Student\Model\StudentQuantsTable;
use Student\Model\StudentMobile;
use Student\Model\StudentMobileTable;
use Student\Model\CarrierQuestion;
use Student\Model\CarrierQuestionTable;
use Student\Model\CarrierAnswers;
use Student\Model\CarrierAnswersTable;
use Questionarie\Model\Question;
use Questionarie\Model\QuestionTable;
use Questionarie\Model\QuestionOption;
use Questionarie\Model\QuestionOptionTable;
use Student\Model\Carrierpath;
use Student\Model\CarrierpathTable;

class StudentController extends AbstractActionController {

    protected $DegreeTable;
    protected $StateTable;
    protected $StudentTable;
    protected $StudentVerbalTable;
    protected $StudentStatusTable;
    protected $QuestionTable;
    protected $QuestionOptionTable;
    protected $StudentQuantsTable;
    protected $StudentMobileTable;
    protected $CarrierQuestionTable;
    protected $CarrierAnswersTable;
    protected $CarrierpathTable;
    protected $UserTable;
    protected $RecoverEmailTable;
    protected $adapter;

    public function getAdapter() {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }

    public function getDegreeTable() {
        if (!$this->DegreeTable) {
            $sm = $this->getServiceLocator();
            $this->DegreeTable = $sm->get('Student\Model\DegreeTable');
        }
        return $this->DegreeTable;
    }
    
    
    public function getCarrierpathTable() {
        if (!$this->CarrierpathTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->CarrierpathTable = $sm->get('Student\Model\CarrierpathTable');
        }
        return $this->CarrierpathTable;
    }

    public function getStateTable() {
        if (!$this->StateTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->StateTable = $sm->get('Student\Model\StateTable');
        }
        return $this->StateTable;
    }

    public function getStudentTable() {
        if (!$this->StudentTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->StudentTable = $sm->get('Student\Model\StudentTable');
        }
        return $this->StudentTable;
    }

    public function getStudentVerbalTable() {
        if (!$this->StudentVerbalTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->StudentVerbalTable = $sm->get('Student\Model\StudentVerbalTable');
        }
        return $this->StudentVerbalTable;
    }

    public function getStudentStatusTable() {
        if (!$this->StudentStatusTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->StudentStatusTable = $sm->get('Student\Model\StudentStatusTable');
        }
        return $this->StudentStatusTable;
    }

    public function getQuestionTable() {
        if (!$this->QuestionTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->QuestionTable = $sm->get('Questionarie\Model\QuestionTable');
        }
        return $this->QuestionTable;
    }

    public function getQuestionOptionTable() {
        if (!$this->QuestionOptionTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->QuestionOptionTable = $sm->get('Questionarie\Model\QuestionOptionTable');
        }
        return $this->QuestionOptionTable;
    }

    public function getStudentQuantsTable() {
        if (!$this->StudentQuantsTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->StudentQuantsTable = $sm->get('Student\Model\StudentQuantsTable');
        }
        return $this->StudentQuantsTable;
    }

    public function getStudentMobileTable() {
        if (!$this->StudentMobileTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->StudentMobileTable = $sm->get('Student\Model\StudentMobileTable');
        }
        return $this->StudentMobileTable;
    }
    
    public function getCarrierQuestionTable() {
        if (!$this->CarrierQuestionTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->CarrierQuestionTable = $sm->get('Student\Model\CarrierQuestionTable');
        }
        return $this->CarrierQuestionTable;
    }
    
    public function getCarrierAnswersTable() {
        if (!$this->CarrierAnswersTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->CarrierAnswersTable = $sm->get('Student\Model\CarrierAnswersTable');
        }
        return $this->CarrierAnswersTable;
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

    public function indexAction() {
        $studentId = '';
        $session = new Container('User');
        $studentId = $session->offsetGet('userId');
        $profilecompleted = 0;
        $profilecompleted = $session->offsetGet('isprofilecompleted');
        if (!$profilecompleted) {
            return $this->redirect()->toRoute('studentregistration');
        }
        return array(
            'studentId' => $studentId
        );
    }

    public function studentRegistrationAction() {
        $session = new Container('User');
        if ($session->offsetExists('email') && $session->offsetGet('roleCode') == 'sa') {
            $this->redirect()->toRoute('admin');
        }
        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        $js_path = $renderer->basePath('js/koolguru/student');
        $headScript = $this->getServiceLocator()->get('viewhelpermanager')
                ->get('headScript');

        $headScript->appendFile($js_path . '/student_registration.js');

        $studentStatus = array();
        $enableTab = array();
        $enableTabContent = array();
        $degreeList = array();
        $stateList = array();
        $verbalQuestions = array();
        $quantQuestions = array();

        $degreeList = $this->getDegreeTable()->getDegreeList();
        $stateList = $this->getStateTable()->getStateList();

        $studentId = '';
        $session = new Container('User');
        $studentId = $session->offsetGet('userId');
        //$studentId = 7;
        $request = $this->getRequest();
        $form = new StudentForm('studentForm', $degreeList, $stateList);
        $form->setInputFilter(new StudentFilter());

        if ($request->isPost()) {
            $data = $request->getPost();
            if (isset($data['regsubmit'])) {
                $email = $data->email;
                $validator = new \Zend\Validator\EmailAddress();
                if ($validator->isValid($email)) {
                    // email appears to be valid
                    // check existence of email in studentTable
                    $validatorEmail = new \Zend\Validator\Db\NoRecordExists(
                            array(
                        'table' => 'student',
                        'field' => 'email',
                        'adapter' => $this->getAdapter()
                            )
                    );
                }
                $form->setData($data);
                if ($validatorEmail->isValid($email)) {
                    $flag = 1;
                } else {
                    $flag = 0;
                }
            } elseif (isset($data['verbalsubmit'])) {
                $flag = 1;
            } elseif (isset($data['quantsubmit'])) {
                $flag = 1;
            }elseif (isset($data['carriersubmit'])) {
                $flag = 1;
            }
            if ($flag) {
                
                if (isset($data['carriersubmit'])) {
                    $carans = array();
                    $carans['student_id'] = $data['student_id']; 
                    unset($data['student_id']);
                    unset($data['carriersubmit']);                    
                    foreach ($data as $key => $det) {
                        $carans['question_id'] = $key; 
                        $split = explode('~', $det);
                        if(isset($split[1])){                            
                            $carans['answer'] = $split[1];
                        }else{
                            $carans['answer'] = $split[0];
                        }
                        $this->getCarrierAnswersTable()->saveCarrierAnswers($carans);
                    }
                    
                    $status['carrier_status'] = 1;
                    $this->getStudentStatusTable()->updateCarrierStatus($status, $studentId);
                    $profile['isprofilecompleted'] = 1;
                    $studentId = $this->getStudentTable()->updateStudentProfile($profile, $studentId);
                   
                }else if (isset($data['quantsubmit'])) {                    
                    $quanttotal = $data['marks_total_quant'];
                    $this->getStudentQuantsTable()->saveStudentQuantsDetail($data);
                    $total = 0;
                    foreach ($data as $key => $det) {
                        $split = explode('~', $det);
                        $total += $split[0];
                    }
                    $status['quant_status'] = 1;
                    
                    $status['marks_obtain_quant'] = $total;
                    $status['marks_total_quant'] = $quanttotal;
                    $status['quant_perc'] = ($total*100/$quanttotal);
                    
                    $this->getStudentStatusTable()->updateQuantStatus($status, $studentId);
                } else if (isset($data['verbalsubmit'])) { 
                    //asd($data,0);
                    $vertotal = $data['marks_total_verbal'];
                    $this->getStudentVerbalTable()->saveStudentVerbalDetail($data);
                    $total = 0;                   
                    foreach ($data as $key => $det) {
                        $split = explode('~', $det);                        
                        $total += $split[0];
                    }
                    $status['verbal_reg_status'] = 1;
                    $status['marks_obtain_verbal'] = $total;
                    $status['marks_total_verbal'] = $vertotal;
                    $status['verbal_perc'] = ($total*100/$vertotal);
                    $this->getStudentStatusTable()->updateVerbalStatus($status, $studentId);
                } else {
                    if (isset($data['regsubmit'])) {
                        if ($form->isValid()) {
                            if (empty($data['student_id'])) {
                                $student_id = $this->getStudentTable()->saveStudent($data);
                                $hashValueReturn = $this->getUserTable()->saveActivationEmail($email);
                                $this->sendActivationLink($email, $hashValueReturn);
                                $status['registration_status'] = 1;
                                $this->getStudentStatusTable()->createStudentStatus($status, $student_id);
                                $this->getStudentMobileTable()->updateMobileStatus($data['mobile'], $student_id);
                                $this->flashMessenger()->setNamespace('success')->addMessage('An email has been sent to your email address, please verify your email address and login to complete your details');
                            } else {
                                $studentId = $this->getStudentTable()->updateStudent($data, $studentId);
                                $this->flashMessenger()->setNamespace('success')->addMessage('Details updated successfully.');
                            }
                        }
                    }
                }
            } else {
                $flashMessage = $this->flashMessenger()->getErrorMessages();
                if (empty($flashMessage)) {
                    $this->flashMessenger()->setNamespace('error')->addMessage('Student Email Id Already Exist, please check your email if you have already registered'
                    );
                }
            }
        }

        $studentDet = array();
        if ($studentId != '') {
            $studentDet = $this->getStudentTable()->getSudent($studentId);
            $form->bind($studentDet);
        }
       
        $form->get('student_id')->setValue($studentId);
        if (!empty($studentId)) {
            $studentStatus = $this->getStudentStatusTable()->getStudentStatus($studentId);
        }
        $enableTab = $this->getStudentTable()->getEnableTabList($studentId, $studentStatus);
        $enableTabContent = $this->getStudentTable()->getEnableTabContentList($studentId, $studentStatus);

        // Tab conytent enable for verbal ability
        if ($enableTabContent[1] == 1) {
            $cond = array();
            $cond['level'] = 1;
            $cond['status'] = 1;
            $questions = array();
            $questions = $this->getQuestionTable()->getStudentQuestions($cond);
            $verbalQuestions = array();
            if (!empty($questions)) {
                foreach ($questions as $ques) {
                    $qoptions = array();
                    $correct_option = '';
                    if (isset($ques['id'])) {
                        $optionlist = array();
                        $options = $this->getQuestionOptionTable()->getOptions($ques['id']);
                        foreach ($options as $v) {
                            $qoptions[$v['id']] = $v['description'];
                            if ($v['is_correct'] == 1) {
                                $correct_option = $v['id'];
                            }
                        }
                    }
                    $t = array();
                    $t['title'] = $ques['name'];
                    $t['options'] = $qoptions;
                    $t['correct'] = $correct_option;
                    $t['maxmark'] = $ques['max_marks'];
                    $t['minmark'] = $ques['min_marks'];
                    $verbalQuestions[$ques['id']] = $t;
                }
            }
        }

        // Tab conytent enable for Quantitative ability
        if ($enableTabContent[2] == 1) {
            $cond = array();
            $cond['level'] = 2;
            $cond['status'] = 1;
            $questions = array();
            $questions = $this->getQuestionTable()->getStudentQuestions($cond);
            if (!empty($questions)) {
                foreach ($questions as $ques) {
                    $qoptions = array();
                    $correct_option = '';
                    if (isset($ques['id'])) {
                        $optionlist = array();
                        $options = $this->getQuestionOptionTable()->getOptions($ques['id']);
                        foreach ($options as $v) {
                            $qoptions[$v['id']] = $v['description'];
                            if ($v['is_correct'] == 1) {
                                $correct_option = $v['id'];
                            }
                        }
                    }
                    $t = array();
                    $t['title'] = $ques['name'];
                    $t['options'] = $qoptions;
                    $t['correct'] = $correct_option;
                    $t['maxmark'] = $ques['max_marks'];
                    $t['minmark'] = $ques['min_marks'];
                    $quantQuestions[$ques['id']] = $t;
                }
            }
        }
        
        // Tab conytent enable for Carrier oriented question
        $carrierquestions = array();
        if ($enableTabContent[3] == 1) {
            $carrierquestions = $this->getCarrierQuestionTable()->getCarrierQuestions();
        }
        
        $carriersuggestedmsg = '';
        $carrier_path = array();
        if ($enableTabContent[4] == 1) {
            $carrieranswrs = $this->getCarrierAnswersTable()->getStudentAnwers($studentId,1);
            $carriermessage = $this->getCarrierAnswersTable()->getCarrierMsg(1,$carrieranswrs[0]['answer']);
            $carriersuggestedmsg = $carriermessage[0]['message'];
            
            $carrier_path = $this->getCarrierpathTable()->getCarrierPath($studentStatus[0]['verbal_perc'],$studentStatus[0]['quant_perc']);
            
        }
        
        return array(
            'form' => $form, 'enableTab' => $enableTab,
            'enableTabContent' => $enableTabContent,
            'verbalQuestions' => $verbalQuestions,
            'student_id' => $studentId,
            'studentStatus' => $studentStatus,
            'quantQuestions' => $quantQuestions,
            'carrierquestions' => $carrierquestions,
            'carriersuggestedmsg' => $carriersuggestedmsg,
            'carrier_path' => $carrier_path,
            'studentDet' => $studentDet,
        );
    }

    /**
     * Function to send a activation link to user
     * @param type $email
     */
    public function sendActivationLink($email, $hashValueReturn) {
        $session = new Container('User');
        $adminEmail = 'support@koolguru.co.in'; //$session->offsetGet('email');
        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        $requesturi = $this->getRequest()->getUri();
        $this->baseUrl = $requesturi->getHost() . $renderer->basePath();
        $url = $requesturi->getScheme() . '://' . $this->baseUrl . '/studentlogin/activatestudent/' . $hashValueReturn;
//subject of the email
        $subject = 'Activate Account';
//body of the email
        $reciever_message = "Dear User $email\n\n 
                             This mail is a system generated email to activate your account...\n\n";
        $reciever_message .= "Please visit $url to activate your account and please set your password.\n\n";
        $reciever_message .= "Note:-\n 1.This link will automatically expire after 72 hours.\n 2.In case your link is expired please write to $adminEmail.\n 3.Please do not reply to this email.";
        $this->getRecoverEmailTable()->sendEmailToUser($subject, $reciever_message, $email);
    }

    public function savemobileAction() {
        $student = array();
        //$mobile = $this->params()->fromRoute('mobile',0);
        $mobile = $_GET['mobile'];
        $student['mobile'] = $mobile;
        $student['isregistered'] = 0;
        $student['student_id'] = 0;
        $student['created'] = time();
        if (!$this->getStudentMobileTable()->getIsMobileExist($mobile)) {
            $this->getStudentMobileTable()->saveStudentMobile($student);
        }
        return false;
    }

}
