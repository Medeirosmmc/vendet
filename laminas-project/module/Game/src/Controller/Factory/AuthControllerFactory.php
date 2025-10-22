<?php
namespace Game\Controller\Factory;

use Game\Controller\AuthController;
use Game\Service\AuthenticationService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AuthControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authService = $container->get(AuthenticationService::class);
        return new AuthController($authService);
    }
}
