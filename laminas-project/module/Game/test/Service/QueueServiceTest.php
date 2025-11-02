<?php
namespace GameTest\Service;

use Game\Service\QueueService;
use Game\Service\QueueMapperInterface;
use Game\Service\QueueableInterface;
use PHPUnit\Framework\TestCase;
use Laminas\EventManager\EventManager;

class QueueServiceTest extends TestCase
{
    private $dbAdapter;

    protected function setUp(): void
    {
        $this->dbAdapter = new \Laminas\Db\Adapter\Adapter([
            'driver'   => 'Pdo_Sqlite',
            'database' => ':memory:',
        ]);

        $this->dbAdapter->query(
            'CREATE TABLE mob_habitaciones_nuevas (
                id_habitacion_nueva INTEGER PRIMARY KEY AUTOINCREMENT,
                id_usuario INTEGER,
                id_edificio INTEGER,
                habitacion TEXT,
                nivel INTEGER,
                fecha_fin DATETIME,
                duracion INTEGER,
                coord TEXT
            )',
            \Laminas\Db\Adapter\Adapter::QUERY_MODE_EXECUTE
        );
    }

    public function testGetQueueWithMapper()
    {
        $queueMapper = new \Game\Service\ConstructionQueueMapper($this->dbAdapter);
        $queueService = new QueueService($queueMapper);

        $eventManager = new EventManager();
        $eventManager->addIdentifiers(['Game\Service\QueueService']);
        $queueService->setEventManager($eventManager);

        $this->assertEquals([], $queueService->getQueue(1, 1));

        $itemMock = $this->createMock(QueueableInterface::class);
        $itemMock->method('getQueueTime')->willReturn(10);
        $itemMock->method('getQueueItemName')->willReturn('test');
        $itemMock->method('getQueueItemLevel')->willReturn(1);
        $itemMock->method('getCoordinates')->willReturn('1:1:1');

        $queueService->addToQueue(1, 1, $itemMock);

        $this->assertCount(1, $queueService->getQueue(1, 1));
    }

    public function testGetQueue()
    {
        $queueMapperMock = $this->createMock(QueueMapperInterface::class);
        $queueMapperMock->method('getQueue')->willReturn([]);

        $queueService = new QueueService($queueMapperMock);

        $this->assertEquals([], $queueService->getQueue(1, 1));
    }

    public function testAddToQueue()
    {
        $queueMapperMock = $this->createMock(QueueMapperInterface::class);
        $queueMapperMock->expects($this->once())->method('addToQueue');

        $queueService = new QueueService($queueMapperMock);

        $eventManager = new EventManager();
        $eventManager->addIdentifiers(['Game\Service\QueueService']);
        $triggered = false;
        $eventManager->attach('addToQueue', function ($e) use (&$triggered) {
            $triggered = true;
        });
        $queueService->setEventManager($eventManager);

        $itemMock = $this->createMock(QueueableInterface::class);

        $queueService->addToQueue(1, 1, $itemMock);

        $this->assertTrue($triggered);
    }
}
