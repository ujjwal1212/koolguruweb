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

class BlogTable {

    protected $tableGateway;

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
        $user = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
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

    

}
