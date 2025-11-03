<?php
namespace Game;

use Game\Service\AuthenticationService as GameAuthenticationService;
use Game\Service\Factory\LaminasAuthServiceFactory;
use Game\Service\PlayerService;
use Game\Service\ConstructionService;
use Game\Service\TrainingService;
use Game\Service\TroopService;
use Game\Service\CombatService;
use Laminas\Authentication\AuthenticationService;
use Laminas\Router\Http\Literal;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Game\Controller\AuthController;
use Game\Controller\Factory\AuthControllerFactory;
use Game\Controller\ConstructionController;
use Game\Controller\Factory\ConstructionControllerFactory;
use Game\Controller\TrainingController;
use Game\Controller\Factory\TrainingControllerFactory;
use Game\Controller\TroopController;
use Game\Controller\Factory\TroopControllerFactory;
use Game\Controller\CombatController;
use Game\Controller\Factory\CombatControllerFactory;
use Laminas\Router\Http\Segment;

return [
    'service_manager' => [
        'factories' => [
            GameAuthenticationService::class => \Laminas\ServiceManager\Factory\ReflectionFactory::class,
            AuthenticationService::class => LaminasAuthServiceFactory::class,
            PlayerService::class => \Laminas\ServiceManager\Factory\ReflectionFactory::class,
            ConstructionService::class => \Laminas\ServiceManager\Factory\ReflectionFactory::class,
            TrainingService::class => \Laminas\ServiceManager\Factory\ReflectionFactory::class,
            TroopService::class => \Laminas\ServiceManager\Factory\ReflectionFactory::class,
            CombatService::class => \Laminas\ServiceManager\Factory\ReflectionFactory::class,
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
            'combat' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/combat[/:action]',
                    'defaults' => [
                        'controller' => CombatController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'troop' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/troop',
                    'defaults' => [
                        'controller' => TroopController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'training' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/training',
                    'defaults' => [
                        'controller' => TrainingController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'construction' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/construction',
                    'defaults' => [
                        'controller' => ConstructionController::class,
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
            ConstructionController::class => ConstructionControllerFactory::class,
            TrainingController::class => TrainingControllerFactory::class,
            TroopController::class => TroopControllerFactory::class,
            CombatController::class => CombatControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
