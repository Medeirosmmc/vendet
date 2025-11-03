<?php
namespace Game\Service;

use Laminas\Db\Adapter\AdapterInterface;

class ConstructionService
{
    private $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function getBuildings($userId)
    {
        $table = new \Laminas\Db\TableGateway\TableGateway('mob_habitaciones', $this->dbAdapter);
        $rowset = $table->select(['id_usuario' => $userId]);
        return $rowset;
    }

    public function incrementBuildingLevel($buildingId, $buildingName)
    {
        $table = new \Laminas\Db\TableGateway\TableGateway('mob_habitaciones', $this->dbAdapter);
        $rowset = $table->select(['id_edificio' => $buildingId]);
        $building = $rowset->current();
        if ($building) {
            $table->update(
                [$buildingName => $building[$buildingName] + 1],
                ['id_edificio' => $buildingId]
            );
        }
    }
}
