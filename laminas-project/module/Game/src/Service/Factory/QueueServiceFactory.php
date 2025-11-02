<?php
namespace Game\Service\Factory;

use Game\Service\QueueService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class QueueServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $queueMapper = new class implements \Game\Service\QueueMapperInterface {
            public function getQueue($userId, $buildingId) {
                return [];
            }
        };

        $queueService = new QueueService($queueMapper);
        $queueService->setEventManager($container->get('EventManager'));
        return $queueService;
    }
}
