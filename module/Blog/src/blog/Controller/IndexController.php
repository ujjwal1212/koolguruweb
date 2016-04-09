<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Db\Sql\Select;
use Blog\Model\Blog;
use Blog\Model\BlogTable;
use Blog\Form\BlogForm;
use Blog\Model\Bloglike;
use Blog\Model\BloglikeTable;
use Subject\Form\SearchForm;
use Student\Model\Student;
use Student\Model\StudentTable;


class IndexController extends AbstractActionController {

    protected $adapter;
    protected $BlogTable;
    protected $StudentTable;
    protected $BloglikeTable;
    

    public function getAdapter() {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }
    
    public function getBlogTable() {
        if (!$this->BlogTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->BlogTable = $sm->get('Blog\Model\BlogTable');
        }
        return $this->BlogTable;
    }
    
    public function getStudentTable() {
        if (!$this->StudentTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->StudentTable = $sm->get('Student\Model\StudentTable');
        }
        return $this->StudentTable;
    }
    
    public function getBloglikeTable() {
        if (!$this->BloglikeTable) {
            $sm = '';
            $sm = $this->getServiceLocator();
            $this->BloglikeTable = $sm->get('Blog\Model\BloglikeTable');
        }
        return $this->BloglikeTable;
    }
    
    
    public function indexAction() { 
        date_default_timezone_set('Asia/Kolkata');
        $months = array('January','February','March','April','May','June','July','August','September','October','November','December');
        $daycount = array(31,28,31,30,31,30,31,31,30,31,30,31);
        
        $years = array();
        $years = $this->getBlogTable()->getBlogyears();
        $curmonth = intval(date('m'));
        $curyear = intval(date('Y'));
        $year_filter = array();         
        foreach($years as $year){
            $timeinit = array();
            for($i=1;$i<=12;$i++){
                $start_time = strtotime($year.'-'.$i.'-'.'01 01:00:00');
                $end_time = strtotime($year.'-'.$i.'-'.$daycount[$i-1].' 01:00:00');
                $t = array();
                $t['start_time'] = $start_time;
                $t['end_time'] = $end_time;
                $timeinit[] = $t;
            }
            $year_filter[$year] = $timeinit;
        }
//        $blogs = $this->getBlogTable()->getBlogs();
//        
//        $blog_detail = array();
//        foreach($blogs as $key=>$dat){
//            $userdet = $this->getBlogTable()->getUserDetail($dat);
//            $username = $userdet[0]['fname'].' '.$userdet[0]['lname'];            
//            $blogs[$key]['username'] = $username;
//        }
        
        
        
        return array('years'=>$years,'year_filter'=>$year_filter,'months'=>$months,'curmonth'=>$curmonth,'curyear' => $curyear);
    }
    
    public function checkloginAction(){
        $session = new Container('User');
        $userId = '';
        $userId = $session->offsetGet('userId');        
        $viewModel = new ViewModel(array(
        ));
        $viewModel->setTerminal(true);
        $this->layout('layout/empty');        
        $response = $this->getResponse();
        if($userId !=''){
            echo 1;
        }else{
            echo 0;
        }
        die;       
    }
    
    public function getblogAction(){
        date_default_timezone_set('Asia/Kolkata');
        $viewModel = new ViewModel(array(
        ));
        $viewModel->setTerminal(true);
        $this->layout('layout/empty');
        $request = $this->getRequest();        
        $response = $this->getResponse();        
        $blogs = array();
        $cond = array();
        $cond['start'] = $_GET['start'];
        $cond['end'] = $_GET['end'];
        $page = $_GET['blogpage'];
        $limit = 5;
        $startpoint = ($page-1)*$limit;        
        $blogs = $this->getBlogTable()->getBlogs($cond,$startpoint,$limit);
        
        foreach($blogs as $key=>$dat){
            $userdet = $this->getBlogTable()->getUserDetail($dat);
            $username = $userdet[0]['fname'].' '.$userdet[0]['lname'];            
            $blogs[$key]['datetime'] = date('h:i A j F Y',$dat['updated_at']);
            $blogs[$key]['username'] = $username;            
            $data = array();
            $data['user_id'] = $dat['updated_by'];
            $data['blog_id'] = $dat['id'];
            $data['is_student'] = $dat['is_student'];
            $blgstatus = array();
            $blgstatus = $this->getBloglikeTable()->getBlogStatus($data); 
            //asd($blgstatus);
            $alt = 0; 
            if(empty($blgstatus)){
                $alt = 0; 
            }else if($blgstatus[0]['status'] == 1){
                $alt = 1; 
            }else if($blgstatus[0]['status'] == 0){
                $alt = 0; 
            }
            $blogs[$key]['alt'] = $alt;
        }  
        //asd($blogs);
        $response->setContent(json_encode($blogs));
        return $response;
    }
    
    public function updatelikeAction(){
        $session = new Container('User');
        
        $viewModel = new ViewModel(array(
        ));
        $viewModel->setTerminal(true);
        $this->layout('layout/empty');
        $request = $this->getRequest();        
        $response = $this->getResponse();
        $blog_id = $_GET['id'];
        $like_status = $_GET['lkstatus'];
        
        $this->getBlogTable()->getUpdateLikeCount($blog_id,$like_status);
        
        
        $data['blog_id'] = $_GET['id'];
        $data['status'] = $_GET['lkstatus'];
        $data['user_id'] = $session->offsetGet('userId');
        $data['is_student'] = $session->offsetGet('is_student');
        $data['created_at'] = time();
        $data['updated_at'] = time();
        $this->getBloglikeTable()->updateStatus($data);
        
        
        $response->setContent(true);
        return $response;
    }
    
    public function addAction() { 
        $msg = '';
        $session = new Container('User');
        $form = new BlogForm('blog');
        $isstudent = 1;
        $isstudent = $session->offsetGet('is_student');
        
        $form->get('is_student')->setValue($isstudent);
        $form->get('created_at')->setValue(time());
        $form->get('created_by')->setValue($session->offsetGet('userId'));
        $form->get('updated_at')->setValue(time());
        $form->get('updated_by')->setValue($session->offsetGet('userId'));
        $form->get('status')->setValue(0);
        $form->get('like_count')->setValue(0);
        $form->get('reply_count')->setValue(0);
        $form->get('post_id')->setValue(0);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost(); 
            //asd($data,0);
            $blog = new Blog();
            $form->setInputFilter($blog->getInputFilter());
            $form->setData($data);
            //asd($blog);
            $data->created_at = time();
            $data->created_by = $session->offsetGet('userId');
            $data->updated_at = time();
            $data->updated_by = $session->offsetGet('userId');
            if ($form->isValid()) {
                $blog->exchangeArray($form->getData());
                $Id = $this->getBlogTable()->saveBlog($blog);
                $msg = 'Your Post is created successfully, It will display on website after some time';
                
                $subject = 'New Blog Created';
                //body of the email
                $username = $session->offsetGet('fname').' '.$session->offsetGet('lname');
                $reciever_message = "Dear Admin \n\n"; 
                $reciever_message .= $username." has created a blog. Please read content and approved it so that it can be display on website.";
                $reciever_message .= "\n\nThanks,\n\n";
                $reciever_message .= "Koolguru Admin";
                $reciever_message .= "\n\nPlease do not reply to this email.";
                $this->getBlogTable()->sendMsg($subject, $reciever_message, 'vatanvindal1985@gmail.com');
            }
        }
        return array('form' => $form,'msg'=>$msg);
    }
    
    public function bloglistAction(){
        $users = array();
        $istudent = array();
        $isadmin = array();
        $student_list = array();
        $student_list = $this->getStudentTable()->fetchAll();
        $admin_list = $this->getStudentTable()->fetchAdmin();
        
        if(!empty($student_list)){
            foreach($student_list as $data){
                $istudent[$data['id']] = $data['fname'].' '.$data['lname'];
            }
        }
        
        if(!empty($admin_list)){
            foreach($admin_list as $data){
                $isadmin[$data['user_id']] = $data['fname'].' '.$data['lname'];
            }
        }
        
        $users['is_student'] = $istudent;
        $users['is_admin'] = $isadmin;
        
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
                $paginator = $this->getBlogTable()->fetchAll(true, $order_by, $order, $searchText);
            }            
        } else {
            // grab the paginator from the CenterTable
            $paginator = $this->getBlogTable()->fetchAll(true, $order_by, $order, $searchText);
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
            'successMsg' => $successMsg,
            'users' => $users
        ));
    }
    
    public function updatestatusAction(){
        $session = new Container('User');
        
        $viewModel = new ViewModel(array(
        ));
        $viewModel->setTerminal(true);
        $this->layout('layout/empty');
        $request = $this->getRequest();        
        $response = $this->getResponse();
       
        $data['blog_id'] = $_GET['blog_id'];
        $data['status'] = $_GET['status'];
        $this->getBlogTable()->updateBlog($data);
        
        $response->setContent(true);
        return $response;
    }

    
}
