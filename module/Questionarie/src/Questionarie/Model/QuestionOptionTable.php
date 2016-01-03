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

class QuestionOptionTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }

    /**
     * Function to Fetch listing for Manage option Page
     * @param type $paginated
     * @param type $searchText
     * @return \Zend\Paginator\Paginator
     */
    public function fetchAll($paginated = false, $order_by = 'id', $order = 'ASC', $searchText = NULL) {

        if ($order_by == 'id' || $order_by == 'name') {
            $order_by = 'l.' . $order_by;
        }
    }

    /**
     * Function to Save option Record to Database.
     * @throws \Exception
     */
    public function saveQuestionOptions($questionData, $questionId) {
        $this->tableGateway->delete(array('questions_id' => $questionId));
        $i = 0;
        foreach ($questionData->option_description as $option) {
            $data['description'] = $option;
            $data['questions_id'] = $questionId;
            $data['is_correct'] = $questionData->option_correct[$i];
            $data['created_date'] = time();
            $data['created_by'] = $questionData->created_by;
            $data['updated_date'] = time();
            $data['updated_by'] = $questionData->created_by;
            if ($this->tableGateway->insert($data)) {
                $optionId = $this->tableGateway->getLastInsertValue();
            }
            $i++;
        }
        return $optionId;
    }

    public function getOptions($questionid) {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select()
                ->from(array('qo' => 'questions_options'))
                ->where(array('questions_id' => $questionid));
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }

}
