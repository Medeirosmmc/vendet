<?php
namespace Game\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Game\Controller\MissionController;
use Game\Service\MissionService;
use Game\Service\TroopService;

class MissionControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new MissionController(
            $container->get(MissionService::class),
            $container->get(TroopService::class)
        );
    }
}
