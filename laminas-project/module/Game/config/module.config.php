<?php
namespace Game;

use Game\Service\AuthenticationService as GameAuthenticationService;
use Game\Service\Factory\AuthenticationServiceFactory;
use Game\Service\Factory\LaminasAuthServiceFactory;
use Game\Service\PlayerService;
use Game\Service\Factory\PlayerServiceFactory;
use Game\Service\ConstructionService;
use Game\Service\Factory\ConstructionServiceFactory;
use Game\Service\TrainingService;
use Game\Service\Factory\TrainingServiceFactory;
use Game\Service\TroopService;
use Game\Service\Factory\TroopServiceFactory;
use Game\Service\CombatService;
use Game\Service\Factory\CombatServiceFactory;
use Game\Service\QueueService;
use Game\Service\Factory\QueueServiceFactory;
use Game\Service\ConstructionQueueMapper;
use Game\Service\Factory\ConstructionQueueMapperFactory;
use Game\Service\TrainingQueueMapper;
use Game\Service\Factory\TrainingQueueMapperFactory;
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
use Game\Command\ProcessQueueCommand;
use Game\Command\Factory\ProcessQueueCommandFactory;
use Game\Listener\ConstructionListener;
use Game\Listener\Factory\ConstructionListenerFactory;
use Game\Listener\TrainingListener;
use Game\Listener\Factory\TrainingListenerFactory;

return [
    'laminas-cli' => [
        'commands' => [
            'game:process-queue' => ProcessQueueCommand::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            ProcessQueueCommand::class => ProcessQueueCommandFactory::class,
            ConstructionListener::class => ConstructionListenerFactory::class,
            'ConstructionQueueServiceEvents' => function ($container) {
                return $container->get('ConstructionQueueService')->getEventManager();
            },
            TrainingListener::class => TrainingListenerFactory::class,
            'TrainingQueueServiceEvents' => function ($container) {
                return $container->get('TrainingQueueService')->getEventManager();
            },
            GameAuthenticationService::class => AuthenticationServiceFactory::class,
            AuthenticationService::class => LaminasAuthServiceFactory::class,
            PlayerService::class => PlayerServiceFactory::class,
            ConstructionService::class => ConstructionServiceFactory::class,
            TrainingService::class => TrainingServiceFactory::class,
            TroopService::class => TroopServiceFactory::class,
            CombatService::class => CombatServiceFactory::class,
            'ConstructionQueueService' => [QueueServiceFactory::class, ['mapper' => 'ConstructionQueueMapper']],
            'TrainingQueueService' => [QueueServiceFactory::class, ['mapper' => 'TrainingQueueMapper']],
            ConstructionQueueMapper::class => ConstructionQueueMapperFactory::class,
            TrainingQueueMapper::class => TrainingQueueMapperFactory::class,
        ],
        'aliases' => [
            'ConstructionQueueMapper' => ConstructionQueueMapper::class,
            'TrainingQueueMapper' => TrainingQueueMapper::class,
        ],
    ],
    'listeners' => [
        'ConstructionListener' => 'ConstructionQueueService',
        'TrainingListener' => 'TrainingQueueService',
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
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/training[/:action[/:id[/:unit]]]',
                    'defaults' => [
                        'controller' => TrainingController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'construction' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/construction[/:action[/:id[/:building]]]',
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
