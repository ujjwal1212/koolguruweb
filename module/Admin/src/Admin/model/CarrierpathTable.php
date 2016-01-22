<?php

namespace Admin\Model;

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

class CarrierpathTable {

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
    public function saveCarrierpath(Carrierpath $carrier) {  
       
        $data = array(
            'name' => trim($carrier->name),
            'min_verbal_perc' => $carrier->min_verbal_perc,
            'max_verbal_perc' => $carrier->max_verbal_perc,
            'min_quant_perc' => $carrier->min_quant_perc,
            'max_quant_perc' => $carrier->max_quant_perc
        );

        $id = (int) $carrier->id;        
        if ($id == 0) {
            $data['created_date'] = time();
            $data['created_by'] = $carrier->created_by;
            $data['updated_date'] = time();
            $data['updated_by'] = $carrier->created_by;
            if ($this->tableGateway->insert($data)) {
                $Id = $this->tableGateway->getLastInsertValue();
            }
        } else {
            if ($this->getCarrier($id)) {
                $data['updated_date'] = time();
                $data['updated_by'] = $carrier->updated_by;
                $Id = $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Carrier id does not exist');
            }
        }
        return $Id;
    }

    public function getCarrier($id) {
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
            $select->where(array('l.id'=>$cond['level']));
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
