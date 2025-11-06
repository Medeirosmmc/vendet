<?php
namespace GameTest\Service;

use Game\Service\ConstructionService;
use Laminas\Db\Adapter\AdapterInterface;
use PHPUnit\Framework\TestCase;

class ConstructionServiceTest extends TestCase
{
    /**
     * @var AdapterInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $dbAdapterMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dbAdapterMock = $this->createMock(AdapterInterface::class);
    }

    public function testConstructionServiceCanBeCreated()
    {
        $service = new ConstructionService($this->dbAdapterMock);
        $this->assertInstanceOf(ConstructionService::class, $service);
    }

    public function testGetBuildingsReturnsBuildingDataOnSuccess()
    {
        // Arrange
        $buildingData = [
            ['id_habitacion' => 1, 'nombre' => 'Barracks'],
            ['id_habitacion' => 2, 'nombre' => 'Factory']
        ];

        $resultMock = $this->createMock(\Laminas\Db\Adapter\Driver\ResultInterface::class);
        // Configure the mock to behave like an iterator
        $resultMock->method('current')->willReturnOnConsecutiveCalls($buildingData[0], $buildingData[1]);
        $resultMock->method('key')->willReturnOnConsecutiveCalls(0, 1);
        $resultMock->method('valid')->willReturnOnConsecutiveCalls(true, true, false);
        $resultMock->method('rewind'); // No return value for void methods
        $resultMock->method('next');   // No return value for void methods

        $statementMock = $this->createMock(\Laminas\Db\Adapter\Driver\StatementInterface::class);
        $statementMock->method('execute')->willReturn($resultMock);

        $driverMock = $this->createMock(\Laminas\Db\Adapter\Driver\DriverInterface::class);
        $driverMock->method('createStatement')->willReturn($statementMock);

        $platformMock = $this->createMock(\Laminas\Db\Adapter\Platform\PlatformInterface::class);
        $platformMock->method('getName')->willReturn('postgresql');

        $this->dbAdapterMock->method('getDriver')->willReturn($driverMock);
        $this->dbAdapterMock->method('getPlatform')->willReturn($platformMock);

        $service = new ConstructionService($this->dbAdapterMock);

        // Act
        $result = $service->getBuildings(1);

        // Assert
        $this->assertEquals($buildingData, $result->toArray());
    }
}
