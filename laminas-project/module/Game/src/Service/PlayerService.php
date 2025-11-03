<?php
namespace Game\Service;

use Laminas\Db\Adapter\AdapterInterface;

class PlayerService
{
    private $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function getPlayer($id)
    {
        $table = new \Laminas\Db\TableGateway\TableGateway('mob_usuarios', $this->dbAdapter);
        $rowset = $table->select(['id_usuario' => $id]);
        return $rowset->current();
    }

    public function getUserByBuildingCoordinates($x, $y, $z)
    {
        $table = new \Laminas\Db\TableGateway\TableGateway('mob_edificios', $this->dbAdapter);
        $rowset = $table->select(['coord1' => $x, 'coord2' => $y, 'coord3' => $z]);
        $building = $rowset->current();
        if ($building) {
            return $this->getPlayer($building->id_usuario);
        }
        return null;
    }

    public function getBuildingByCoordinates($x, $y, $z)
    {
        $table = new \Laminas\Db\TableGateway\TableGateway('mob_edificios', $this->dbAdapter);
        $rowset = $table->select(['coord1' => $x, 'coord2' => $y, 'coord3' => $z]);
        return $rowset->current();
    }
}
