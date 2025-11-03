<?php
namespace Game\Listener;

use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;

use Game\Service\TrainingService;

class TrainingListener extends AbstractListenerAggregate
{
    private $trainingService;

    public function __construct(TrainingService $trainingService)
    {
        $this->trainingService = $trainingService;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach('processQueue.item', [$this, 'onProcessQueueItem']);
    }

    public function onProcessQueueItem($event)
    {
        $item = $event->getParam('item');
        if (!isset($item['entrenamiento'])) {
            return;
        }
        $this->trainingService->addUnitsToPlayer($item['id_usuario'], $item['entrenamiento'], 1);
    }
}
