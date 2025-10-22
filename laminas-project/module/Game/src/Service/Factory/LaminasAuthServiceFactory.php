<?php
namespace Game\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\Session as SessionStorage;
use Laminas\ServiceManager\Factory\FactoryInterface;

class LaminasAuthServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $storage = new SessionStorage('Laminas_Auth');
        $authAdapter = $container->get(\Game\Service\AuthenticationService::class);
        return new AuthenticationService($storage, $authAdapter);
    }
}
