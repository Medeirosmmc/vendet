<?php
namespace Game\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

use Game\Service\AuthenticationService;

class AuthController extends AbstractActionController
{
    private $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function loginAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->authService->setUsername($data['username']);
            $this->authService->setPassword($data['password']);

            $result = $this->authService->authenticate();

            if ($result->isValid()) {
                return $this->redirect()->toRoute('game');
            } else {
                $view = new ViewModel(['error' => 'Invalid credentials.']);
                $view->setTemplate('game/auth/login');
                return $view;
            }
        }

        return new ViewModel();
    }
}
