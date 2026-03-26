<?php
namespace GameTest\Service;

use Game\Service\BattleReportService;
use Game\Service\BattleReportMapper;
use PHPUnit\Framework\TestCase;

class BattleReportServiceTest extends TestCase
{
    private $dbAdapter;

    protected function setUp(): void
    {
        $this->dbAdapter = new \Laminas\Db\Adapter\Adapter([
            'driver'   => 'Pdo_Sqlite',
            'database' => ':memory:',
        ]);

        $this->dbAdapter->query(
            'CREATE TABLE mob_batallas (
                id_batalla INTEGER PRIMARY KEY AUTOINCREMENT,
                atacante INTEGER,
                defensor INTEGER,
                html TEXT
            )',
            \Laminas\Db\Adapter\Adapter::QUERY_MODE_EXECUTE
        );
    }

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

    public function testSaveAndGetReportWithMapper()
    {
        $battleReportMapper = new \Game\Service\BattleReportMapper($this->dbAdapter);
        $battleReportService = new BattleReportService($battleReportMapper);

        $reportData = [
            'atacante' => 1,
            'defensor' => 2,
            'html' => '{"winner":"attacker"}',
        ];

        $reportId = $battleReportService->saveReport($reportData);
        $this->assertEquals(1, $reportId);

        $report = $battleReportService->getReport($reportId);
        $this->assertEquals($reportData['atacante'], $report->atacante);
        $this->assertEquals($reportData['defensor'], $report->defensor);
        $this->assertEquals($reportData['html'], $report->html);
    }
}
