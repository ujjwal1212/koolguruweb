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


class IndexController extends AbstractActionController {

    protected $adapter;
    protected $BlogTable;
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
                
            }
            
        }
        return array('form' => $form,'msg'=>$msg);
    }

    
}