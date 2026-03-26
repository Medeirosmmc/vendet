<?php
namespace Game\Model\Mapper;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Sql\Sql;
use Game\Service\QueueMapperInterface;
use Game\Model\Entity\Mission;

class MissionQueueMapper implements QueueMapperInterface
{
    protected $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function fetchQueueItems($limit = 100)
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select('mob_misiones')
            ->where->lessThanOrEqualTo('fecha_fin', date('Y-m-d H:i:s'));
        $select->limit($limit);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $missions = [];
        foreach ($result as $row) {
            $missions[] = new Mission($row);
        }
        return $missions;
    }

    public function removeFinishedItems()
    {
        $sql = new Sql($this->adapter);
        $delete = $sql->delete('mob_misiones')
            ->where->lessThanOrEqualTo('fecha_fin', date('Y-m-d H:i:s'));

        $stmt = $sql->prepareStatementForSqlObject($delete);
        $stmt->execute();
    }

    public function get($id)
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select('mob_misiones')->where(['id_mision' => $id]);
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        $row = $result->current();
        return $row ? new Mission($row) : null;
    }
}
