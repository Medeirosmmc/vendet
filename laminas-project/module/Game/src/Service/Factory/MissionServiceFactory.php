<?php
namespace Game\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Game\Service\MissionService;
use Game\Model\Mapper\MissionMapper;
use Game\Service\PlayerService;
use Game\Service\TroopService;

class MissionServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new MissionService(
            new MissionMapper($container->get(\Laminas\Db\Adapter\AdapterInterface::class)),
            $container->get(PlayerService::class),
            $container->get('MissionQueueService'),
            $container->get(TroopService::class)
        );
    }
}
