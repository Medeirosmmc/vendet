<?php
namespace Game\Model\Mapper;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql\Select;
use Game\Model\Entity\Mission;

class MissionMapper
{
    protected $adapter;
    protected $tableGateway;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->tableGateway = new TableGateway('mob_misiones', $this->adapter);
    }

    public function fetchAll($userId)
    {
        $select = new Select($this->tableGateway->getTable());
        $select->where(['id_usuario' => $userId]);
        $select->order('fecha_fin ASC');

        $resultSet = $this->tableGateway->selectWith($select);

        $missions = [];
        foreach ($resultSet as $row) {
            $missions[] = new Mission((array)$row);
        }
        return $missions;
    }

    public function get($missionId)
    {
        $resultSet = $this->tableGateway->select(['id_mision' => $missionId]);
        $row = $resultSet->current();
        if (!$row) {
            return null;
        }
        return new Mission((array)$row);
    }

    public function save(Mission $mission)
    {
        $data = $mission->getArrayCopy();
        unset($data['id_mision']);

        if (!isset($data['cantidad'])) {
            $tropas = json_decode($data['tropas'], true);
            $data['cantidad'] = array_sum($tropas);
        }

        if (empty($mission->id_mision)) {
            $this->tableGateway->insert($data);
            return $this->tableGateway->getLastInsertValue();
        } else {
            $this->tableGateway->update($data, ['id_mision' => $mission->id_mision]);
            return $mission->id_mision;
        }
    }

    public function delete($missionId)
    {
        $this->tableGateway->delete(['id_mision' => $missionId]);
    }
}
