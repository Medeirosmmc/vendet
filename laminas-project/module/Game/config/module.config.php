<?php
namespace Game;

use Game\Service\AuthenticationService as GameAuthenticationService;
use Game\Service\Factory\AuthenticationServiceFactory;
use Game\Service\Factory\LaminasAuthServiceFactory;
use Game\Service\PlayerService;
use Game\Service\Factory\PlayerServiceFactory;
use Laminas\Authentication\AuthenticationService;
use Laminas\Router\Http\Literal;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Game\Controller\AuthController;
use Game\Controller\Factory\AuthControllerFactory;
use Laminas\Router\Http\Segment;

return [
    'service_manager' => [
        'factories' => [
            GameAuthenticationService::class => AuthenticationServiceFactory::class,
            AuthenticationService::class => LaminasAuthServiceFactory::class,
            PlayerService::class => PlayerServiceFactory::class,
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
            'player_profile' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/player/profile/:id',
                    'constraints' => [
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => \Game\Controller\PlayerController::class,
                        'action'     => 'profile',
                    ],
                ],
            ],
            'logout' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'logout',
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
            \Game\Controller\PlayerController::class => \Game\Controller\Factory\PlayerControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
