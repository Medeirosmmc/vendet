<?php
namespace Game\Controller\Factory;

use Game\Controller\CombatController;
use Game\Service\CombatService;
use Game\Service\TroopService;
use Game\Service\PlayerService;
use Game\Service\BattleReportService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class CombatControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $combatService = $container->get(CombatService::class);
        $troopService = $container->get(TroopService::class);
        $playerService = $container->get(PlayerService::class);
        $battleReportService = $container->get(BattleReportService::class);
        return new CombatController($combatService, $troopService, $playerService, $battleReportService);
    }
}
