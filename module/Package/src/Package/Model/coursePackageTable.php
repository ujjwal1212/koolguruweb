<?php

namespace Package\Model;

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

class coursePackageTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    }

    /**
     * Function to Save Question Record to Database.
     * @throws \Exception
     */
    public function deleteMapping($packageId) {
        $this->tableGateway->delete(array('package_id' => (int) $packageId));
    }

    public function savePackageCourse($data, $packageId) {
        $session = new Container('User');
        if (!empty($data->courseId)) {
            $this->deleteMapping($packageId);
            foreach ($data->courseId as $courseId) {
                $courseData = array(
                    'package_id' => $packageId,
                    'course_id' => $courseId,
                    'created_at' => time(),
                    'updated_at' => time(),
                    'created_by' => $session->offsetGet('userId'),
                    'updated_by' => $session->offsetGet('userId'),
                );
                $this->tableGateway->insert($courseData);
            }
        }
    }

    public function getCourseMapData($id) {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(array('cp' => 'package_course_map'))
                ->join(array('c' => 'course'), 'cp.course_id=c.id', array('title'), 'left');
        $select->columns(array('course_id'));
        $select->where(array('cp.package_id' => $id));

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultset = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $resultset;
    }

}
