<?php
namespace GameTest\Service;

use Game\Service\BattleReportService;
use Game\Service\BattleReportMapper;
use PHPUnit\Framework\TestCase;

class BattleReportServiceTest extends TestCase
{
    public function testGetReport()
    {
        $battleReportMapperMock = $this->createMock(BattleReportMapper::class);
        $battleReportMapperMock->method('getReport')->willReturn([]);

        $battleReportService = new BattleReportService($battleReportMapperMock);

        $this->assertEquals([], $battleReportService->getReport(1));
    }

    public function testSaveReport()
    {
        $battleReportMapperMock = $this->createMock(BattleReportMapper::class);
        $battleReportMapperMock->method('saveReport')->willReturn(1);

        $battleReportService = new BattleReportService($battleReportMapperMock);

        $this->assertEquals(1, $battleReportService->saveReport([]));
    }
}
