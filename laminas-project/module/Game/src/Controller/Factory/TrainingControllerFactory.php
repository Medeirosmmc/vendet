<?php
namespace Game\Controller\Factory;

use Game\Controller\TrainingController;
use Game\Service\TrainingService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class TrainingControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $trainingService = $container->get(TrainingService::class);
        return new TrainingController($trainingService);
    }
}
