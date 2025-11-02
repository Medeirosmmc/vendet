<?php
namespace Game\Service;

use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\EventManager\EventManagerInterface;

class QueueService implements EventManagerAwareInterface
{
    protected $eventManager;
    protected $queueMapper;

    public function __construct(QueueMapperInterface $queueMapper)
    {
        $this->queueMapper = $queueMapper;
    }

    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers([
            __CLASS__,
            get_class($this)
        ]);
        $this->eventManager = $eventManager;
    }

    public function getEventManager()
    {
        return $this->eventManager;
    }

    public function getQueue($userId, $buildingId)
    {
        return $this->queueMapper->getQueue($userId, $buildingId);
    }
}
