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

class CoursesubjectTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }

   /**
     * Function to Save Category Record to Database.
     * @throws \Exception
     */
    public function saveCaurseSubjectMap($data) {
        if ($this->tableGateway->insert($data)) {
            $Id = $this->tableGateway->getLastInsertValue();
        }
        return $Id;
    }
    
    public function deleteCourseSubjectMappingLevel($subjectid) {
        $sql = new Sql($this->tableGateway->getAdapter());
        $delete = $sql->delete('course_subject_map')->where("subject_id = $subjectid");
        $statement = $sql->prepareStatementForSqlObject($delete);
        $statement->execute();
    }
    
    public function updateCaurseSubjectMap($data,$subjectid) {
        $Id = $this->tableGateway->update($data, array('subject_id' => $subjectid));
    }
    
    
}
