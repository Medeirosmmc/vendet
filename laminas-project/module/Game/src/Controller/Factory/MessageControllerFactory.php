<?php
namespace Game\Controller\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Game\Controller\MessageController;
use Game\Service\MessageService;
use Game\Service\PlayerService;

class MessageControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new MessageController(
            $container->get(MessageService::class),
            $container->get(PlayerService::class)
        );
    }
}
