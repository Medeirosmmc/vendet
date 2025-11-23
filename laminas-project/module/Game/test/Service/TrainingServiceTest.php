<?php
namespace GameTest\Service;

use Game\Service\TrainingService;
use Laminas\Db\Adapter\AdapterInterface;
use PHPUnit\Framework\TestCase;

class TrainingServiceTest extends TestCase
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

    public function testTrainingServiceCanBeCreated()
    {
        $service = new TrainingService($this->dbAdapterMock);
        $this->assertInstanceOf(TrainingService::class, $service);
    }

    public function testGetTrainingQueueReturnsTrainingDataOnSuccess()
    {
        // Arrange
        $trainingData = [
            ['id_entrenamiento' => 1, 'nombre' => 'Swordsman'],
            ['id_entrenamiento' => 2, 'nombre' => 'Archer']
        ];

        $resultMock = $this->createMock(\Laminas\Db\Adapter\Driver\ResultInterface::class);
        $resultMock->method('current')->willReturnOnConsecutiveCalls($trainingData[0], $trainingData[1]);
        $resultMock->method('key')->willReturnOnConsecutiveCalls(0, 1);
        $resultMock->method('valid')->willReturnOnConsecutiveCalls(true, true, false);
        $resultMock->method('rewind');
        $resultMock->method('next');

        $statementMock = $this->createMock(\Laminas\Db\Adapter\Driver\StatementInterface::class);
        $statementMock->method('execute')->willReturn($resultMock);

        $driverMock = $this->createMock(\Laminas\Db\Adapter\Driver\DriverInterface::class);
        $driverMock->method('createStatement')->willReturn($statementMock);

        $platformMock = $this->createMock(\Laminas\Db\Adapter\Platform\PlatformInterface::class);
        $platformMock->method('getName')->willReturn('postgresql');

        $this->dbAdapterMock->method('getDriver')->willReturn($driverMock);
        $this->dbAdapterMock->method('getPlatform')->willReturn($platformMock);

        $service = new TrainingService($this->dbAdapterMock);

        // Act
        $result = $service->getTrainingQueue(1);

        // Assert
        $this->assertEquals($trainingData, $result->toArray());
    }
}
