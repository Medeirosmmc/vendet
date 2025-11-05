<?php
namespace Game\Listener\Factory;

use Game\Listener\TrainingListener;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Game\Service\TrainingService;

class TrainingListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new TrainingListener($container->get(TrainingService::class));
    }
}
