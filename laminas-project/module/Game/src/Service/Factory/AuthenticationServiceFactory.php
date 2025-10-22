<?php
namespace Game\Service\Factory;

use Game\Service\AuthenticationService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Laminas\Db\Adapter\AdapterInterface;

class AuthenticationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        return new AuthenticationService($dbAdapter);
    }
}
