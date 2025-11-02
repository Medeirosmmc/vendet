<?php
namespace Game\Service\Factory;

use Game\Service\QueueService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class QueueServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $queueMapper = $container->get('ConstructionQueueMapper');
        $queueService = new QueueService($queueMapper);
        $queueService->setEventManager($container->get('EventManager'));
        return $queueService;
    }
}
