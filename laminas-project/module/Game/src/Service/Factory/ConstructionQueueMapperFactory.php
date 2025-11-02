<?php
namespace Game\Service\Factory;

use Game\Service\ConstructionQueueMapper;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ConstructionQueueMapperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        return new ConstructionQueueMapper($dbAdapter);
    }
}
