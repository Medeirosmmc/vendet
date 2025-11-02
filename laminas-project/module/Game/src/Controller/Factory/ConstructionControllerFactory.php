<?php
namespace Game\Controller\Factory;

use Game\Controller\ConstructionController;
use Game\Service\ConstructionService;
use Game\Service\QueueService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ConstructionControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $constructionService = $container->get(ConstructionService::class);
        $queueService = $container->get('ConstructionQueueService');
        return new ConstructionController($constructionService, $queueService);
    }
}
