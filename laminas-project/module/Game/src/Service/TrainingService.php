<?php
namespace Game\Service;

use Laminas\Db\Adapter\AdapterInterface;

class TrainingService
{
    private $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function getTrainingQueue($userId)
    {
        $table = new \Laminas\Db\TableGateway\TableGateway('mob_entrenamientos_nuevos', $this->dbAdapter);
        $rowset = $table->select(['id_usuario' => $userId]);
        return $rowset;
    }

    public function addUnitsToPlayer($userId, $unitName, $quantity)
    {
        $table = new \Laminas\Db\TableGateway\TableGateway('mob_tropas', $this->dbAdapter);
        $rowset = $table->select(['id_usuario' => $userId]);
        $player = $rowset->current();
        if ($player) {
            $table->update(
                [$unitName => $player[$unitName] + $quantity],
                ['id_usuario' => $userId]
            );
        }
    }

    public function getTraining($userId, $trainingName)
    {
        $table = new \Laminas\Db\TableGateway\TableGateway('mob_entrenamientos', $this->dbAdapter);
        $rowset = $table->select(['id_usuario' => $userId]);
        return $rowset->current();
    }

    public function getSchoolLevel($buildingId)
    {
        $table = new \Laminas\Db\TableGateway\TableGateway('mob_habitaciones', $this->dbAdapter);
        $rowset = $table->select(['id_edificio' => $buildingId]);
        $building = $rowset->current();
        return $building ? $building->escuela : 0;
    }
}
