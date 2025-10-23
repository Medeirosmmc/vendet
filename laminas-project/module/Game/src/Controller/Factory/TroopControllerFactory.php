<?php
namespace Game\Controller\Factory;

use Game\Controller\TroopController;
use Game\Service\TroopService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class TroopControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $troopService = $container->get(TroopService::class);
        return new TroopController($troopService);
    }
}
