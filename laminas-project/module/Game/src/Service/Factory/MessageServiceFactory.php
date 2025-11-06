<?php
namespace Game\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Game\Service\MessageService;
use Game\Model\Mapper\MessageMapper;

class MessageServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new MessageService(
            new MessageMapper(
                $container->get(\Laminas\Db\Adapter\AdapterInterface::class)
            )
        );
    }
}
