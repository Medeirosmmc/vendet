<?php
namespace Game;

use Game\Service\AuthenticationService;
use Game\Service\Factory\AuthenticationServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Game\Controller\AuthController;
use Game\Controller\Factory\AuthControllerFactory;
use Laminas\Router\Http\Segment;

return [
    'service_manager' => [
        'factories' => [
            AuthenticationService::class => AuthenticationServiceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'game' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/game',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'login' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            AuthController::class => AuthControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
