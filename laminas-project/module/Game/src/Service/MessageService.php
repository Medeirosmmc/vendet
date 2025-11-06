<?php
namespace Game\Service;

use Game\Model\Mapper\MessageMapper;
use Game\Model\Entity\Message;

class MessageService
{
    protected $messageMapper;

    public function __construct(MessageMapper $messageMapper)
    {
        $this->messageMapper = $messageMapper;
    }

    public function getMessagesForUser($userId, $folder = 0)
    {
        return $this->messageMapper->fetchAll($userId, $folder);
    }

    public function getMessageById($messageId)
    {
        return $this->messageMapper->get($messageId);
    }

    public function sendMessage(Message $message)
    {
        return $this->messageMapper->save($message);
    }

    public function deleteMessage($messageId, $userId)
    {
        $this->messageMapper->delete($messageId, $userId);
    }
}
