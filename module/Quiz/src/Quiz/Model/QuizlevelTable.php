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

class QuizlevelTable {

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
        $select->columns(array('id', 'code', 'title'));
        $select->order($order_by . ' ' . $order);
        if (isset($searchText) && trim($searchText) != '') {
            $select->where->like('p.title', "%" . $searchText . "%")
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
    public function saveQuizLevel($data) {  
        if ($this->tableGateway->insert($data)) {
            $mappingid = $this->tableGateway->getLastInsertValue();
        }        
        return $mappingid;
    }

    public function getQuizlevel($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function getQuizlevelByQuiz($id) {        
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('ql' => 'quiz_level'));
        //$select->columns(array('id', 'title', 'code', 'status', 'image_path','description','price','duration','relevant_for','advantage','ff_classroom','whatuserget'));
        $select->where(array('ql.quiz_id' => $id));

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
        
    }
    
    
    public function deleteQuizLevel($id) {
        $sql = new Sql($this->tableGateway->getAdapter());
        $delete = $sql->delete('quiz_level')->where("quiz_id = $id");
        $statement = $sql->prepareStatementForSqlObject($delete);
        $statement->execute();
    }

   

}
