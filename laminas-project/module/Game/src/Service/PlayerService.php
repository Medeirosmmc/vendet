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
}
