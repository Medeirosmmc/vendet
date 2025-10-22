<?php
namespace Game\Controller\Factory;

use Game\Controller\PlayerController;
use Game\Service\PlayerService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class PlayerControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $playerService = $container->get(PlayerService::class);
        return new PlayerController($playerService);
    }
}
