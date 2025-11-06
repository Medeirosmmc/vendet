<?php
namespace GameTest\Service;

use PHPUnit\Framework\TestCase;
use Game\Service\MessageService;
use Game\Model\Mapper\MessageMapper;
use Game\Model\Entity\Message;

class MessageServiceTest extends TestCase
{
    protected $messageMapper;
    protected $messageService;

    protected function setUp(): void
    {
        $this->messageMapper = $this->createMock(MessageMapper::class);
        $this->messageService = new MessageService($this->messageMapper);
    }

    public function testGetMessagesForUser()
    {
        $userId = 1;
        $message = new Message(['id_mensaje' => 1, 'asunto' => 'Test']);
        $this->messageMapper->expects($this->once())
            ->method('fetchAll')
            ->with($userId)
            ->willReturn([$message]);

        $result = $this->messageService->getMessagesForUser($userId);

        $this->assertCount(1, $result);
        $this->assertSame($message, $result[0]);
    }

    public function testGetMessageById()
    {
        $messageId = 1;
        $message = new Message(['id_mensaje' => 1, 'asunto' => 'Test']);
        $this->messageMapper->expects($this->once())
            ->method('get')
            ->with($messageId)
            ->willReturn($message);

        $result = $this->messageService->getMessageById($messageId);

        $this->assertSame($message, $result);
    }

    public function testSendMessage()
    {
        $message = new Message(['asunto' => 'Test']);
        $this->messageMapper->expects($this->once())
            ->method('save')
            ->with($message)
            ->willReturn(1);

        $result = $this->messageService->sendMessage($message);

        $this->assertEquals(1, $result);
    }

    public function testDeleteMessage()
    {
        $messageId = 1;
        $userId = 1;
        $this->messageMapper->expects($this->once())
            ->method('delete')
            ->with($messageId, $userId);

        $this->messageService->deleteMessage($messageId, $userId);
    }
}
