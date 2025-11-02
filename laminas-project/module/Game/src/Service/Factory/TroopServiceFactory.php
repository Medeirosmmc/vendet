<?php
namespace Game\Service\Factory;

use Game\Service\TroopService;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class TroopServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        $config = $container->get('config');
        return new TroopService($dbAdapter, $config['troops']);
    }
}
