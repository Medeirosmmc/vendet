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
}
