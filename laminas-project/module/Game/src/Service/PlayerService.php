<?php
namespace Game\Service;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\TableGateway;
use Game\Model\Entity\Building;

class PlayerService
{
    private $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function getPlayer($id)
    {
        $table = new TableGateway('mob_usuarios', $this->dbAdapter);
        $rowset = $table->select(['id_usuario' => $id]);
        return $rowset->current();
    }

    public function getBuildingById($buildingId)
    {
        $table = new TableGateway('mob_edificios', $this->dbAdapter);
        $rowset = $table->select(['id_edificio' => $buildingId]);
        $row = $rowset->current();
        if ($row) {
            return new Building((array) $row);
        }
        return null;
    }

    public function getUserByBuildingCoordinates($x, $y, $z)
    {
        $table = new TableGateway('mob_edificios', $this->dbAdapter);
        $rowset = $table->select(['coord1' => $x, 'coord2' => $y, 'coord3' => $z]);
        $building = $rowset->current();
        if ($building) {
            return $this->getPlayer($building->id_usuario);
        }
        return null;
    }

    public function getBuildingByCoordinates($x, $y, $z)
    {
        $table = new TableGateway('mob_edificios', $this->dbAdapter);
        $rowset = $table->select(['coord1' => $x, 'coord2' => $y, 'coord3' => $z]);
        return $rowset->current();
    }

    public function getTrainingLevel($userId, $trainingName)
    {
        $table = new TableGateway('mob_entrenamientos', $this->dbAdapter);
        $rowset = $table->select(['id_usuario' => $userId, 'entrenamiento' => $trainingName]);
        $row = $rowset->current();
        return $row ? $row->nivel : 0;
    }

    public function updateTrainingLevel($userId, $trainingName, $newLevel)
    {
        $table = new TableGateway('mob_entrenamientos', $this->dbAdapter);
        $existing = $table->select(['id_usuario' => $userId, 'entrenamiento' => $trainingName])->current();

        if ($existing) {
            $table->update(['nivel' => $newLevel], ['id_usuario' => $userId, 'entrenamiento' => $trainingName]);
        } else {
            $table->insert(['id_usuario' => $userId, 'entrenamiento' => $trainingName, 'nivel' => $newLevel]);
        }
    }
}
