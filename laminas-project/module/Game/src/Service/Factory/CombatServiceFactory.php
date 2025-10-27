<?php
namespace Game\Service\Factory;

use Game\Service\CombatService;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Game\Service\TroopService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class CombatServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        $troopService = $container->get(TroopService::class);
        return new CombatService($dbAdapter, $troopService);
    }
}
