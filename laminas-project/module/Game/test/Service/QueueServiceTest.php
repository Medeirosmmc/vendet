<?php
namespace GameTest\Service;

use Game\Service\QueueService;
use Game\Service\QueueMapperInterface;
use Game\Service\QueueableInterface;
use PHPUnit\Framework\TestCase;
use Laminas\EventManager\EventManager;

class QueueServiceTest extends TestCase
{
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
