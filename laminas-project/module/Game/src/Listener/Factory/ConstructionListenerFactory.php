<?php
namespace Game\Listener\Factory;

use Game\Listener\ConstructionListener;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ConstructionListenerFactory implements FactoryInterface
{
use Game\Service\ConstructionService;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ConstructionListener($container->get(ConstructionService::class));
    }
}
