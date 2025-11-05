<?php
namespace GameTest\Service;

use Game\Service\PlayerService;
use Laminas\Db\Adapter\AdapterInterface;
use PHPUnit\Framework\TestCase;

class PlayerServiceTest extends TestCase
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

    public function testPlayerServiceCanBeCreated()
    {
        $service = new PlayerService($this->dbAdapterMock);
        $this->assertInstanceOf(PlayerService::class, $service);
    }

    public function testGetPlayerReturnsPlayerDataOnSuccess()
    {
        // Arrange
        $playerData = ['id_usuario' => 1, 'nombre' => 'Test Player'];

        // Create a mock result set that will be returned by the mocked statement
        $resultMock = $this->createMock(\Laminas\Db\Adapter\Driver\ResultInterface::class);
        $resultMock->method('current')->willReturn($playerData);

        // Create a mock statement
        $statementMock = $this->createMock(\Laminas\Db\Adapter\Driver\StatementInterface::class);
        $statementMock->method('execute')->willReturn($resultMock);

        // Create a mock driver that returns the mock statement
        $driverMock = $this->createMock(\Laminas\Db\Adapter\Driver\DriverInterface::class);
        $driverMock->method('createStatement')->willReturn($statementMock);

        // Create a mock platform
        $platformMock = $this->createMock(\Laminas\Db\Adapter\Platform\PlatformInterface::class);
        $platformMock->method('getName')->willReturn('postgresql');

        // Configure the main dbAdapter mock to use the mock driver and platform
        $this->dbAdapterMock->method('getDriver')->willReturn($driverMock);
        $this->dbAdapterMock->method('getPlatform')->willReturn($platformMock);

        $service = new PlayerService($this->dbAdapterMock);

        // Act
        $result = $service->getPlayer(1);

        // Assert
        $this->assertEquals($playerData, (array) $result);
    }
}
