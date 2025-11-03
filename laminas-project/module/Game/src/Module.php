<?php
namespace Game;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Mvc\MvcEvent;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);

        $serviceManager = $e->getApplication()->getServiceManager();
        $config = $serviceManager->get('config');

        if (!empty($config['listeners'])) {
            foreach ($config['listeners'] as $listener => $service) {
                $serviceManager->get($listener)->attach($serviceManager->get($service . 'Events'));
            }
        }
    }
    {
        $controller = $e->getTarget();
        $controllerName = $e->getRouteMatch()->getParam('controller', null);
        $actionName = $e->getRouteMatch()->getParam('action', null);

        $authService = $e->getApplication()->getServiceManager()->get('Laminas\Authentication\AuthenticationService');

        if ($controllerName != 'Game\Controller\AuthController' && !$authService->hasIdentity()) {
            $router = $e->getRouter();
            $url = $router->assemble([], ['name' => 'login']);
            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);
            $response->sendHeaders();
            exit;
        }
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $serviceManager = $e->getApplication()->getServiceManager();
        $config = $serviceManager->get('config');

        foreach ($config['listeners'] as $listener) {
            $serviceManager->get($listener)->attach($serviceManager->get($listener . 'Events'));
        }
    }
}
