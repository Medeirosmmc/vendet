<?php
namespace Game\Service;

use Laminas\Db\Adapter\AdapterInterface;

class TrainingQueueMapper implements QueueMapperInterface
{
    private $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function getQueue($userId, $buildingId)
    {
        $tableGateway = new \Laminas\Db\TableGateway\TableGateway('mob_entrenamientos_nuevos', $this->dbAdapter);
        $rowset = $tableGateway->select(['id_usuario' => $userId, 'id_edificio' => $buildingId]);
        return $rowset->toArray();
    }

    public function addToQueue($userId, $buildingId, QueueableInterface $item)
    {
        $tableGateway = new \Laminas\Db\TableGateway\TableGateway('mob_entrenamientos_nuevos', $this->dbAdapter);

        $select = $tableGateway->getSql()->select();
        $select->where(['id_edificio' => $buildingId])->order('id_entrenamiento_nuevo DESC')->limit(1);

        $lastInQueue = $tableGateway->selectWith($select)->current();

        $finishTime = empty($lastInQueue) ? time() + $item->getQueueTime() : strtotime($lastInQueue->fecha_fin) + $item->getQueueTime();

        $tableGateway->insert([
            'id_usuario' => $userId,
            'id_edificio' => $buildingId,
            'entrenamiento' => $item->getQueueItemName(),
            'nivel' => $item->getQueueItemLevel(),
            'fecha_fin' => date('Y-m-d H:i:s', $finishTime),
            'duracion' => $item->getQueueTime(),
            'coord' => $item->getCoordinates(),
        ]);
    }

    public function getFinishedItems()
    {
        $tableGateway = new \Laminas\Db\TableGateway\TableGateway('mob_entrenamientos_nuevos', $this->dbAdapter);
        $rowset = $tableGateway->select(['fecha_fin < ?' => date('Y-m-d H:i:s')]);
        return $rowset->toArray();
    }

    public function removeFinishedItems()
    {
        $tableGateway = new \Laminas\Db\TableGateway\TableGateway('mob_entrenamientos_nuevos', $this->dbAdapter);
        $tableGateway->delete(['fecha_fin < ?' => date('Y-m-d H:i:s')]);
    }
}
