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
}
