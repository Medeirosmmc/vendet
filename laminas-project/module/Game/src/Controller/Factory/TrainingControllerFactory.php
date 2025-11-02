<?php
namespace Game\Controller\Factory;

use Game\Controller\TrainingController;
use Game\Service\TrainingService;
use Game\Service\QueueService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class TrainingControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $trainingService = $container->get(TrainingService::class);
        $queueService = $container->get('TrainingQueueService');
        return new TrainingController($trainingService, $queueService);
    }
}
