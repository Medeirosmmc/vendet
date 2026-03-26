<?php
namespace Game\Listener;

use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;
use Game\Service\TrainingService;
use Game\Service\PlayerService;
use Game\Service\QueueableInterface;

class TrainingListener extends AbstractListenerAggregate
{
    private $trainingService;
    private $playerService;

    public function __construct(TrainingService $trainingService, PlayerService $playerService)
    {
        $this->trainingService = $trainingService;
        $this->playerService = $playerService;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedManager = $events->getSharedManager();
        $this->listeners[] = $sharedManager->attach(
            'TrainingQueueService',
            'item.processed',
            [$this, 'onProcessQueueItem'],
            $priority
        );
    }

    public function onProcessQueueItem($event)
    {
        $item = $event->getParam('item');
        if (!$item instanceof QueueableInterface || $item->getQueueType() !== 'unit') {
            return;
        }

        $userId = $item->getOwnerId();
        $trainingName = $item->getQueueItemName();

        // Correct logic: Increment the training level
        $currentLevel = $this->playerService->getTrainingLevel($userId, $trainingName);
        $newLevel = $currentLevel + 1;
        $this->playerService->updateTrainingLevel($userId, $trainingName, $newLevel);
    }
}
