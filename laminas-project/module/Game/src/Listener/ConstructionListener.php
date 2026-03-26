<?php
namespace Game\Listener;

use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;

use Game\Service\ConstructionService;

class ConstructionListener extends AbstractListenerAggregate
{
    private $constructionService;

    public function __construct(ConstructionService $constructionService)
    {
        $this->constructionService = $constructionService;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach('processQueue.item', [$this, 'onProcessQueueItem']);
    }

    public function onProcessQueueItem($event)
    {
        $item = $event->getParam('item');
        if (!isset($item['habitacion'])) {
            return;
        }
        $this->constructionService->incrementBuildingLevel($item['id_edificio'], $item['habitacion']);
    }
}
