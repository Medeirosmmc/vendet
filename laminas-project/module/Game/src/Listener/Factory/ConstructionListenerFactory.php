<?php
namespace Game\Listener\Factory;

use Game\Listener\ConstructionListener;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Game\Service\ConstructionService;

class ConstructionListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ConstructionListener($container->get(ConstructionService::class));
    }
}
