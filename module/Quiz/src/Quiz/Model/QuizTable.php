<?php

namespace Quiz\Model;

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

class QuizTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }

    /**
     * Function to Fetch listing for Manage Subject Page
     * @param type $paginated
     * @param type $searchText
     * @return \Zend\Paginator\Paginator
     */
    public function fetchAll($paginated = false, $order_by = 'id', $order = 'ASC', $searchText = NULL) {

        if ($order_by == 'id' || $order_by == 'title' || $order_by == 'code') {
            $order_by = 'q.' . $order_by;
        }

        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('q' => 'quiz'));
        $select->columns(array('id', 'code', 'title','pass_percentage'));
        $select->join(array('s'=>'subjects'),'s.id = q.subject_id',array('subjecttitle'=>'title'),'left');
        $select->join(array('c'=>'chapters'),'c.id = q.chapter_id',array('chaptertitle'=>'title'),'left');
        $select->order($order_by . ' ' . $order);
        if (isset($searchText) && trim($searchText) != '') {
            $select->where->like('q.title', "%" . $searchText . "%")
            ->or->like('q.code', "%" . $searchText . "%")
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
     * Function to Save Package Record to Database.
     * @throws \Exception
     */
    public function saveQuiz(Quiz $quiz) {        
        $data = array(
            'title' => trim($quiz->title),
            'subject_id' => $quiz->subject_id,
            'chapter_id' => $quiz->chapter_id,
            'code' => $quiz->code,            
            'status' => $quiz->status,    
            'description' => $quiz->description,
            'pass_percentage' => $quiz->pass_percentage
        );
        $id = (int) $quiz->id;
        if ($id == 0) {
            $data['created_at'] = time();
            $data['created_by'] = $quiz->created_by;
            $data['updated_at'] = time();
            $data['updated_by'] = $quiz->created_by;
            if ($this->tableGateway->insert($data)) {
                $quizId = $this->tableGateway->getLastInsertValue();
            }
        } else {
            if ($this->getQuiz($id)) {
                $data['updated_at'] = time();
                $data['updated_by'] = $quiz->updated_by;
                $quizId = $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Quiz does not exist');
            }
        }        
        return $quizId;
    }

    public function getQuiz($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
   

}
