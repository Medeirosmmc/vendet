<?php
namespace Game\Service;

class BattleReportService
{
    private $battleReportMapper;

    public function __construct(BattleReportMapper $battleReportMapper)
    {
        $this->battleReportMapper = $battleReportMapper;
    }

    public function getReport($reportId)
    {
        return $this->battleReportMapper->getReport($reportId);
    }

    public function saveReport($reportData)
    {
        return $this->battleReportMapper->saveReport($reportData);
    }
}
