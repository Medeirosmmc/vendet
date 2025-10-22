<?php
namespace Game\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Game\Service\AuthenticationService;
use Laminas\Session\Container;

class AuthController extends AbstractActionController
{
    private $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function loginAction()
    {
        $session = new Container('login');
        $maxAttempts = 5;
        $lockoutTime = 60; // 1 minuto

        if ($session->attempts >= $maxAttempts && $session->timestamp + $lockoutTime > time()) {
            $remainingTime = $session->timestamp + $lockoutTime - time();
            $error = "Too many failed login attempts. Please try again in {$remainingTime} seconds.";
            return new ViewModel(['error' => $error]);
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->authService->setUsername($data['username']);
            $this->authService->setPassword($data['password']);

            $result = $this->authService->authenticate();

            if ($result->isValid()) {
                $session->getManager()->getStorage()->clear('login');
                return $this->redirect()->toRoute('game');
            } else {
                $session->attempts = ($session->attempts ?? 0) + 1;
                $session->timestamp = time();
                $error = 'Invalid credentials.';
                if ($session->attempts >= $maxAttempts) {
                    $error = "Too many failed login attempts. Please try again in {$lockoutTime} seconds.";
                }
                $view = new ViewModel(['error' => $error]);
                $view->setTemplate('game/auth/login');
                return $view;
            }
        }

        return new ViewModel();
    }

    public function logoutAction()
    {
        $authService = $this->getEvent()->getApplication()->getServiceManager()->get('Laminas\Authentication\AuthenticationService');
        $authService->clearIdentity();
        return $this->redirect()->toRoute('login');
    }
}
