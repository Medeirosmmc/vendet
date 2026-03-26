<?php
namespace Game\Service;

use Laminas\Db\Adapter\AdapterInterface;

class BattleReportMapper
{
    private $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function getReport($reportId)
    {
        $tableGateway = new \Laminas\Db\TableGateway\TableGateway('mob_batallas', $this->dbAdapter);
        $rowset = $tableGateway->select(['id_batalla' => $reportId]);
        return $rowset->current();
    }

    public function saveReport($reportData)
    {
        $tableGateway = new \Laminas\Db\TableGateway\TableGateway('mob_batallas', $this->dbAdapter);
        $tableGateway->insert($reportData);
        return $this->dbAdapter->getDriver()->getLastGeneratedValue();
    }
}
