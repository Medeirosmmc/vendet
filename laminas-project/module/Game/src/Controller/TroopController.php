<?php
namespace Game\Controller;

use Game\Service\TroopService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class TroopController extends AbstractActionController
{
    private $troopService;

    public function __construct(TroopService $troopService)
    {
        $this->troopService = $troopService;
    }

    public function indexAction()
    {
        $userId = $this->identity();
        $troops = $this->troopService->getTroops($userId);

        return new ViewModel(['troops' => $troops]);
    }
}
