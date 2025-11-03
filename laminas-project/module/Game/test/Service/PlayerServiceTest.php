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
}
