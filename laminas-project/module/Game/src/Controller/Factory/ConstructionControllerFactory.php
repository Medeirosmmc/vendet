<?php
namespace Game\Controller\Factory;

use Game\Controller\ConstructionController;
use Game\Service\ConstructionService;
use Game\Service\QueueService;
use Game\Service\BuildingDataService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ConstructionControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $constructionService = $container->get(ConstructionService::class);
        $queueService = $container->get('ConstructionQueueService');
        $buildingDataService = $container->get(BuildingDataService::class);
        return new ConstructionController($constructionService, $queueService, $buildingDataService);
    }
}
