<?php
namespace ZF2AuthAcl\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;


class User extends AbstractTableGateway
{

    public $table = 'users';
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function getUsers($where = array(), $columns = array())
    {
        try {
            $sql = new Sql($this->getAdapter());
            $select = $sql->select()->from(array(
                'user' => $this->table
            ));
            
            if (count($where) > 0) {
                $select->where($where);
            }
            
            if (count($columns) > 0) {
                $select->columns($columns);
            }
            $select->join(array('userRole' => 'user_role'), 'userRole.user_id = user.user_id', array('role_id'), 'LEFT');
            $select->join(array('role' => 'role'), 'userRole.role_id = role.rid', array('role_name','role_code'), 'LEFT');
            $select->join(array('center_users' => 'center_users'), 'userRole.user_id = center_users.user_id', array('center_id'), 'LEFT');
            $select->join(array('center' => 'center'), 'center_users.center_id = center.id', array('center_code'=>'center_id','center_type'), 'LEFT');
            
            $statement = $sql->prepareStatementForSqlObject($select);
            $users = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
            return $users;
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage());
        }
    }            
}
