<?php

namespace Questionarie\Model;

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

class QuestionTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }

    /**
     * Function to Fetch listing for Manage question Page
     * @param type $paginated
     * @param type $searchText
     * @return \Zend\Paginator\Paginator
     */
    public function fetchAll($paginated = false, $order_by = 'id', $order = 'ASC', $searchText = NULL) {

        if ($order_by == 'id' || $order_by == 'name') {
            $order_by = 'q.' . $order_by;
        }

        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('q' => 'questions'));
        $select->columns(array('id', 'name', 'description'));
        $select->order($order_by . ' ' . $order);
        if (isset($searchText) && trim($searchText) != '') {
            $select->where->like('q.name', "%" . $searchText . "%")
            ->or->like('q.id', "%" . $searchText . "%");
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
    public function saveQuestion(Question $Question) {
        $data = array(
            'name' => trim($Question->name),
            'description' => $Question->description,
            'level' => $Question->level,
            'min_marks' => $Question->min_marks,
            'max_marks' => $Question->max_marks,
            'type' => $Question->type,
            'category_id' => $Question->category_id,
            'status' => $Question->status
        );

        $id = (int) $Question->id;
        if ($id == 0) {
            $data['created_date'] = time();
            $data['created_by'] = $Question->created_by;
            $data['updated_date'] = time();
            $data['updated_by'] = $Question->created_by;
            if ($this->tableGateway->insert($data)) {
                $questionId = $this->tableGateway->getLastInsertValue();
            }
        } else {
            if ($this->getQuestion($id)) {
                $data['updated_date'] = time();
                $data['updated_by'] = $Question->updated_by;
                $questionId = $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Question id does not exist');
            }
        }
        return $questionId;
    }

    public function getQuestion($id) {
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
                ->join(array('c'=>'category'),'c.id = q.category_id',array('category_name'=>'title'),'left')
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
            $select->where(array('l.name'=>$cond['level']));
        }
        
        if(isset($cond['status'])){
            $select->where(array('q.status'=>$cond['status']));
        }
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }
    
    public function getExcerciseQuestions($cond) {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('q' => 'questions'))
                ->join(array('l'=>'level'),'q.level = l.id',array(),'left')
                ->join(array('qo'=>'questions_options'),'q.id = qo.questions_id',array('option_id'=>'id','option_description'=>'description','is_correct'=>'is_correct'),'left');
        
                
        $select->columns(array('id', 'description','min_marks','max_marks'));
        
        if(isset($cond['id'])){
            $select->where(array('q.id'=>$cond['id']));
        }
        
        if(isset($cond['level'])){
            $select->where(array('l.name'=>$cond['level']));
        }
        
        if(isset($cond['status'])){
            $select->where(array('q.status'=>$cond['status']));
        }
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }

}
