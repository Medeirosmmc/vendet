<?php
namespace Game\Service\Factory;

use Game\Service\BattleReportMapper;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class BattleReportMapperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        return new BattleReportMapper($dbAdapter);
    }
}
