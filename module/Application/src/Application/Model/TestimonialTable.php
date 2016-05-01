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

class TestimonialTable {

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
    public function getTestimonial($id=0) {

        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('t' => 'testimonial'));
        $select->columns(array('id','name', 'short_description','description','image','degree'));
        $select->where(array('t.status'=>1));
        if($id > 0){
            $select->where(array('t.id'=>$id));
        }
        
       
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }
}
