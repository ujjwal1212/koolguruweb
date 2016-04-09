<?php

namespace Blog\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
Use Zend\Db\Sql\Expression;
use Zend\Session\Container;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BlogTable implements ServiceLocatorAwareInterface{

    protected $tableGateway;

    protected $services;

    public function setServiceLocator(ServiceLocatorInterface $locator) {
        $this->services = $locator;
    }
    
    public function getServiceLocator() {
        return $this->services;
    }
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }
    
    public function saveBlog(Blog $blog) {        
        $data = array(
            'title' => trim($blog->title),
            'post' => trim($blog->post),
            'status' => $blog->status,            
            'is_student' => $blog->is_student,
            'post_id' => $blog->post_id

        );

        $id = (int) $blog->id;
        if ($id == 0) {            
            $data['created_at'] = time();
            $data['created_by'] = $blog->created_by;
            $data['updated_at'] = time();
            $data['updated_by'] = $blog->created_by; 
            if ($this->tableGateway->insert($data)) {
                $Id = $this->tableGateway->getLastInsertValue();
            }
        } else {
            if ($this->getBlog($id)) {
                $data['updated_at'] = time();
                $data['updated_by'] = $blog->updated_by;
                $Id = $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Course id does not exist');
            }
        }
        return $Id;
    }
    
    public function getBlog($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    
    public function getBlogs($cond = array(),$start,$limit){
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('bp' => 'blog_post'));
                //->join(array('l'=>'level'),'q.level = l.id',array('level_name'=>'name'),'left');
        $select->columns(array('id', 'title', 'post','updated_by','updated_at','like_count','reply_count','is_student'));
        if(isset($cond['start']) && isset($cond['end'])){
            $select->where ('bp.updated_at between '.$cond['start'].' and '.$cond['end']);
        }
        $select->order('bp.updated_at DESC');
        $select->where ('bp.status=1');
        $select->limit($limit); // always takes an integer/numeric
        $select->offset($start);
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }
    
    public function getUserDetail($data){
        
        $user = array();
        if($data['is_student'] == 1){
            $sql = new Sql($this->tableGateway->getAdapter());
            $select = $sql->select();
            $select->from(array('st' => 'student'));
                    //->join(array('l'=>'level'),'q.level = l.id',array('level_name'=>'name'),'left');
            $select->columns(array('fname','lname'));
            $select->where(array('st.id'=>$data['updated_by']));

            $statement = $sql->prepareStatementForSqlObject($select);
            $user = $this->resultSetPrototype->initialize($statement->execute())
                    ->toArray();
        }else{
            $sql = new Sql($this->tableGateway->getAdapter());
            $select = $sql->select();
            $select->from(array('st' => 'users'));
                    //->join(array('l'=>'level'),'q.level = l.id',array('level_name'=>'name'),'left');
            $select->columns(array('fname', 'lname'));
            $select->where(array('st.user_id'=>$data['updated_by']));

            $statement = $sql->prepareStatementForSqlObject($select);
            $user = $this->resultSetPrototype->initialize($statement->execute())
                    ->toArray();
        }
        return $user;
    }
    
    public function getBlogyears(){
        $resultset = array();
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('bp' => 'blog_post'));                
        $select->columns(array('updated_at'));
        //$select->where(array('st.id'=>$data['updated_by']));

        $statement = $sql->prepareStatementForSqlObject($select);
//        $user = $this->resultSetPrototype->initialize($statement->execute())
//                ->toArray();
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        
        $years = array();
        if(!empty($resultset)){
            $year_first = date('Y',$resultset[0]['updated_at']);
            $year_top = date('Y',$resultset[count($resultset)-1]['updated_at']);
            //$year_top = 2020;
            if($year_first == $year_top){
                $years[] = $year_first;
            }else{            
                for($i=$year_top; $i>=$year_first; $i--){                
                   $years[] = $i; 
                }
            }
        }
        return $years;
    }
    
    public function getUpdateLikeCount($blog_id,$like_status){
        $resultset = array();
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('bp' => 'blog_post'));                
        $select->columns(array('like_count'));
        $select->where(array('bp.id'=>$blog_id));
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        
        if(!empty($resultset)){
            $like_count = $resultset[0]['like_count'];
            if($like_status == 1){
                $like_count = $like_count + 1;
            }else{
                $like_count = $like_count - 1;
            }
            $data = array();
            $data['like_count'] = $like_count;
            $this->tableGateway->update($data, array('id' => $blog_id));
        }
    }
    
    
    public function updateBlog($data){
        $resultset = array();
        $sql = new Sql($this->tableGateway->getAdapter());
        $blog_id = $data['blog_id'];
        unset($data['blog_id']);
        $this->tableGateway->update($data, array('id' => $blog_id));
    }
    
    
    public function sendMsg($subject, $reciever_message, $email,$cc='anubhawsri1985@gmail.com'){
        $message = new Message();
        // Setup SMTP transport using LOGIN authentication
        
        $get_smtp_details = $this->getServiceLocator()->get('Config');
        $smtp_details = $get_smtp_details['smtp_details'];
        $admin_email = $smtp_details['connection_config']['username'];
        $message->addTo($email)
                ->addTo($cc)
                ->addFrom($admin_email, 'No-reply Koolguru')
                ->setSubject($subject)
                ->setBody($reciever_message);

        // Setup SMTP transport using LOGIN authentication
        $get_smtp_details = $this->getServiceLocator()->get('Config');
        $smtp_details = $get_smtp_details['smtp_details'];
        $transport = new SmtpTransport();
        $options = new SmtpOptions(array(
            'name' => $smtp_details['name'],
            'host' => $smtp_details['host'],
            'connection_class' => $smtp_details['connection_class'],
            'port' => $smtp_details['port'],
            'connection_config' => array(
                'ssl' => $smtp_details['connection_config']['ssl'], /* Page would hang without this line being added */
                'username' => $smtp_details['connection_config']['username'],
                'password' => $smtp_details['connection_config']['password'],
            ),
        ));
        $transport->setOptions($options);
        $transport->send($message);
    }
    
    
    /**
     * Function to Fetch listing for Manage Subject Page
     * @param type $paginated
     * @param type $searchText
     * @return \Zend\Paginator\Paginator
     */
    public function fetchAll($paginated = false, $order_by = 'id', $order = 'ASC', $searchText = NULL) {
        if ($order_by == 'id' || $order_by == 'title' || $order_by == 'post') {
            $order_by = 'bp.' . $order_by;
        }
        
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('bp' => 'blog_post'));
        $select->columns(array('id', 'title', 'post','status','updated_at','updated_by','is_student'));        
        $select->order('bp.status DESC');
        $select->order($order_by . ' ' . $order);
        if (isset($searchText) && trim($searchText) != '') {
            $select->where->like('bp.title', "%" . $searchText . "%")
            ->or->like('bp.post', "%" . $searchText . "%")
            ->or->like('bp.id', "%" . $searchText . "%");
        }
//        $statement = $sql->prepareStatementForSqlObject($select);
        if ($paginated) {
            $resultSetPrototype = new ResultSet();
            $paginatorAdapter = new DbSelect(
                    // our configured select object
                    $select,
                    // the adapter to run it against
                    $this->tableGateway->getAdapter(),
                    // the result set to hydrate
                    $resultSetPrototype
            );
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }
}
