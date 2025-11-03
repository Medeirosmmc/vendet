<?php
namespace Game\Command\Factory;

use Game\Command\ProcessQueueCommand;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ProcessQueueCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ProcessQueueCommand(
            $container->get('ConstructionQueueService'),
            $container->get('TrainingQueueService')
        );
    }
}
