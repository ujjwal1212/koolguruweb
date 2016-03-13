<?php

namespace Application\Model;

use Application\Model\Chapter;
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

class ChapterTable {

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
    
    public function getDemoChapter() {        
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('c' => 'chapters'))
                ->join(array('sc'=>'subject_chapter_map'),'sc.chapter_id=c.id')
                ->join(array('s'=>'subjects'),'s.id = sc.subject_id',array('chapter_id'=>'id','subject_title'=>'title'),'left');
        $select->columns(array('chapter_id'=>'id', 'demo_chapter_title'=>'title','chapter_content'=>'content'));
        $select->where(array('c.isdemo'=>1));
        $select->where(array('c.status'=>1));
        $select->where(array('s.status'=>1));

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }
    
    public function getDemoQuiz(){
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('c' => 'chapters'))
                ->join(array('sc'=>'subject_chapter_map'),'sc.chapter_id=c.id',array('subject_id'=>'subject_id'))
                ->join(array('q'=>'quiz'),'q.chapter_id=c.id AND q.subject_id=sc.subject_id', array('quiz_id'=>'id','pass_percentage'=>'pass_percentage'))
                ->join(array('ql'=>'quiz_level'),'ql.quiz_id=q.id',array('level_id'=>'level_id','category_id'=>'category_id','ques_nos'=>'ques_nos'));
        $select->columns(array('chapter_id'=>'id'));
        $select->where(array('c.isdemo'=>1));
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }
    

}
