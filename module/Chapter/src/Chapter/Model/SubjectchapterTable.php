<?php

namespace Chapter\Model;

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

class SubjectchapterTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }
    
    

    /**
     * Function to Save Question Record to Database.
     * @throws \Exception
     */
    public function deleteMapping($subject,$chapter){
        $this->tableGateway->delete(array('subject_id' => (int) $subject,'chapter_id' => (int) $chapter));
    }
    public function saveMapping($data) {
        $Id = 0;
        if ($this->tableGateway->insert($data)) {
            $Id = $this->tableGateway->getLastInsertValue();
        }
        return $Id;
    }

    

}
