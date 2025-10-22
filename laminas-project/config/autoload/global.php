<?php
use Laminas\Db\Adapter\AdapterAbstractServiceFactory;

return [
    'service_manager' => [
        'abstract_factories' => [
            AdapterAbstractServiceFactory::class,
        ],
    ],
];
