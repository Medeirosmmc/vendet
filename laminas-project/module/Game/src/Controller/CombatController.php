<?php
namespace Game\Controller;

use Game\Service\CombatService;
use Game\Service\TroopService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CombatController extends AbstractActionController
{
    private $combatService;
    private $troopService;

    public function __construct(CombatService $combatService, TroopService $troopService)
    {
        $this->combatService = $combatService;
        $this->troopService = $troopService;
    }

    public function indexAction()
    {
        $userId = $this->identity()->id_usuario;
        $troops = $this->troopService->getTroops($userId);
        return new ViewModel(['troops' => $troops]);
    }

    public function attackAction()
    {
        $attackerId = $this->identity()->id_usuario;
        $defenderId = 2; // placeholder
        $attackingTroops = ['soldado' => 10]; // placeholder

        $result = $this->combatService->calculateCombat($attackingTroops, []);

        // TODO: Save battle report

        return $this->redirect()->toRoute('combat', ['action' => 'report', 'id' => 1]);
    }

    public function reportAction()
    {
        return new ViewModel();
    }
}
