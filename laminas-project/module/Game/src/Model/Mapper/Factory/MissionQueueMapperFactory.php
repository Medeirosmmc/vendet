<?php
namespace Game\Model\Mapper\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Game\Model\Mapper\MissionQueueMapper;

class MissionQueueMapperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new MissionQueueMapper(
            $container->get(\Laminas\Db\Adapter\AdapterInterface::class)
        );
    }
}
