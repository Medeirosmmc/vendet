<?php
namespace GameTest\Service;

use Game\Service\QueueService;
use Game\Service\QueueMapperInterface;
use PHPUnit\Framework\TestCase;

class QueueServiceTest extends TestCase
{
    public function testGetQueue()
    {
        $queueMapperMock = $this->createMock(QueueMapperInterface::class);
        $queueMapperMock->method('getQueue')->willReturn([]);

        $queueService = new QueueService($queueMapperMock);

        $this->assertEquals([], $queueService->getQueue(1, 1));
    }
}
