<?php

namespace Student\Model;

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

class CourseTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }
    
    public function getNewCourseCode(){
        $resultset = array();
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('c' => 'course'));
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        
        $count = count($resultset);
        $key = '';
        if($count > 0){
            $key = 'KGC'.($count+1);
        }else{
            $key = 'KGC1';
        }
        return $key;
    }

    /**
     * Function to Fetch listing for Manage question Page
     * @param type $paginated
     * @param type $searchText
     * @return \Zend\Paginator\Paginator
     */
    public function fetchAll($paginated = false, $order_by = 'id', $order = 'ASC', $searchText = NULL) {

        if ($order_by == 'id' || $order_by == 'name') {
            $order_by = 'c.' . $order_by;
        }

        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('c' => 'course'));
        $select->columns(array('id', 'title', 'code','description','status','isdemo','image_path'));
        $select->order($order_by . ' ' . $order);
        if (isset($searchText) && trim($searchText) != '') {
            $select->where->like('c.title', "%" . $searchText . "%")
            ->or->like('c.description', "%" . $searchText . "%")
            ->or->like('c.code', "%" . $searchText . "%");
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

    /**
     * Function to Save Question Record to Database.
     * @throws \Exception
     */
    public function saveCourse(Course $course) {  
        $data = array(
            'title' => trim($course->title),
            'description' => trim($course->description),
            'status' => $course->status,
            'isdemo' => $course->isdemo, 
            'image_path' => $course->image_path
        );

        $id = (int) $course->id;        
        if ($id == 0) {
            $data['code'] = $course->code;
            $data['created_at'] = time();
            $data['created_by'] = $course->created_by;
            $data['updated_at'] = time();
            $data['updated_by'] = $course->created_by;            
            if ($this->tableGateway->insert($data)) {
                $Id = $this->tableGateway->getLastInsertValue();
            }
        } else {
            if ($this->getCourse($id)) {
                $data['updated_at'] = time();
                $data['updated_by'] = $course->updated_by;
                $Id = $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Course id does not exist');
            }
        }
        return $Id;
    }

    public function getCourse($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function getQuestionDetails($id) {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('q' => 'questions'))
                ->join(array('l'=>'level'),'q.level = l.id',array('level_name'=>'name'),'left');
        $select->columns(array('id', 'name', 'description','type','status','min_marks','max_marks'));
        $select->where(array('q.id'=>$id));

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }
    
    public function getStudentQuestions($cond) {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('q' => 'questions'))
                ->join(array('l'=>'level'),'q.level = l.id',array('level_name'=>'name','level_id'=>'id'),'left');
                
        $select->columns(array('id', 'name', 'description','type','status','min_marks','max_marks'));
        
        if(isset($cond['id'])){
            $select->where(array('q.id'=>$cond['id']));
        }
        
        if(isset($cond['level'])){
            $select->where(array('l.id'=>$cond['level']));
        }
        
        if(isset($cond['status'])){
            $select->where(array('q.status'=>$cond['status']));
        }
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }
    
    
    public function getCarrierPath($verbal,$quant) {        
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('cp' => 'carrier_path'));
        $select->columns(array('id', 'name','msg'));
        $select->where('cp.min_verbal_perc <= '.$verbal);
        $select->where('cp.max_verbal_perc >= '.$verbal);
        
        $select->where('cp.min_quant_perc <= '.$quant);
        $select->where('cp.max_quant_perc >= '.$quant);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }

}
