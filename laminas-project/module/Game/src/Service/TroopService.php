<?php
namespace Game\Service;

use Laminas\Db\Adapter\AdapterInterface;

class TroopService
{
    private $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function getTroops($userId)
    {
        $table = new \Laminas\Db\TableGateway\TableGateway('mob_tropas', $this->dbAdapter);
        $rowset = $table->select(['id_usuario' => $userId]);
        return $rowset;
    }
}
