<?php
namespace Game\Service\Factory;

use Game\Service\BattleReportService;
use Game\Service\BattleReportMapper;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class BattleReportServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $battleReportMapper = $container->get(BattleReportMapper::class);
        return new BattleReportService($battleReportMapper);
    }
}
