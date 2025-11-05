<?php
namespace Game\Service\Factory;

use Game\Service\TrainingDataService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class TrainingDataServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        return new TrainingDataService($config['training']);
    }
}
