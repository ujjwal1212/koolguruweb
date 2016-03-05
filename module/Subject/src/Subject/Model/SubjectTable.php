<?php

namespace Subject\Model;

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

class SubjectTable {

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
            $order_by = 's.' . $order_by;
        }

        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('s' => 'subjects'));
        $select->columns(array('id', 'code', 'title'));
        $select->order($order_by . ' ' . $order);
        if (isset($searchText) && trim($searchText) != '') {
            $select->where->like('s.title', "%" . $searchText . "%")
            ->or->like('s.code', "%" . $searchText . "%")
            ->or->like('s.id', "%" . $searchText . "%");
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
     * Function to Save Subject Record to Database.
     * @throws \Exception
     */
    public function saveSubject(Subject $subject) {
        $data = array(
            'title' => trim($subject->title),
            'code' => $subject->code,
            'image_path' => $subject->image_path,
            'status' => $subject->status,
            //'isdemo' => $subject->isdemo,
        );
//        if ($data['isdemo'] == '1') {
//            $updateData['isdemo'] = 0;
//            $this->tableGateway->update($updateData);
//        }
        $id = (int) $subject->id;
        if ($id == 0) {
            $data['created_at'] = time();
            $data['created_by'] = $subject->created_by;
            $data['updated_at'] = time();
            $data['updated_by'] = $subject->created_by;
            if ($this->tableGateway->insert($data)) {
                $subjectId = $this->tableGateway->getLastInsertValue();
            }
        } else {
            if ($this->getSubject($id)) {
                $data['updated_at'] = time();
                $data['updated_by'] = $subject->updated_by;
                $subjectId = $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Subject id does not exist');
            }
        }
        return $subjectId;
    }

    public function getSubject($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getSubjectDetails($id) {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('s' => 'subjects'));
        $select->columns(array('id', 'title', 'code', 'status', 'image_path'));
        $select->where(array('s.id' => $id));

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }
    
    public function getSubjectList() {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('s' => 'subjects'));
        $select->columns(array('id', 'title'));
        

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        
        return $resultset;
    }
    
    public function getChapterSubjects($chapterid) {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('scm' => 'subject_chapter_map'));
        $select->columns(array('chapter_id','subject_id'));
        $select->join(array('s'=>'subjects'),'s.id = scm.subject_id',array('subjecttitle'=>'title'),'left');
        $select->where(array('scm.chapter_id' => $chapterid));
        

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        
        return $resultset;
    }
    
    public function getSubjectsChapter($subjectid) {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('scm' => 'subject_chapter_map'));
        $select->columns(array('chapter_id'));
        $select->join(array('c'=>'chapters'),'c.id = scm.chapter_id',array('chaptertitle'=>'title'),'left');
        $select->where(array('scm.subject_id' => $subjectid));
        

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        
        return $resultset;
    }
    
   
    
    
   

}
