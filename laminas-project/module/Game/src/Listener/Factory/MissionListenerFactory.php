<?php
namespace Game\Listener\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Game\Listener\MissionListener;
use Game\Service\MissionService;
use Game\Service\CombatService;
use Game\Service\PlayerService;
use Game\Service\TroopService;
use Game\Service\BattleReportService;

class MissionListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new MissionListener(
            $container->get(MissionService::class),
            $container->get(CombatService::class),
            $container->get(PlayerService::class),
            $container->get(TroopService::class),
            $container->get(BattleReportService::class)
        );
    }
}
