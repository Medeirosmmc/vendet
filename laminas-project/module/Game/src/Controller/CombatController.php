<?php
namespace Game\Controller;

use Game\Service\CombatService;
use Game\Service\TroopService;
use Game\Service\PlayerService;
use Game\Service\BattleReportService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CombatController extends AbstractActionController
{
    private $combatService;
    private $troopService;
    private $playerService;
    private $battleReportService;

    public function __construct(CombatService $combatService, TroopService $troopService, PlayerService $playerService, BattleReportService $battleReportService)
    {
        $this->combatService = $combatService;
        $this->troopService = $troopService;
        $this->playerService = $playerService;
        $this->battleReportService = $battleReportService;
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

        $coordX = (int) $this->params()->fromPost('coord_x');
        $coordY = (int) $this->params()->fromPost('coord_y');
        $coordZ = (int) $this->params()->fromPost('coord_z');
        $attackingTroops = $this->params()->fromPost('troops');

        $defender = $this->playerService->getUserByBuildingCoordinates($coordX, $coordY, $coordZ);
        if (!$defender) {
            // TODO: Handle case where defender is not found
            return $this->redirect()->toRoute('combat');
        }
        $defenderId = $defender->id_usuario;

        $defenderBuilding = $this->playerService->getBuildingByCoordinates($coordX, $coordY, $coordZ);
        $defenderTroops = $this->troopService->getTroops($defenderBuilding->id_edificio);

        $result = $this->combatService->calculateCombat($attackingTroops, $defenderTroops->toArray());

        $reportId = $this->battleReportService->saveReport([
            'atacante' => $attackerId,
            'defensor' => $defenderId,
            'html' => json_encode($result),
        ]);

        return $this->redirect()->toRoute('combat', ['action' => 'report', 'id' => $reportId]);
    }

    public function reportAction()
    {
        $reportId = (int) $this->params()->fromRoute('id', 0);
        $report = $this->battleReportService->getReport($reportId);
        return new ViewModel(['report' => $report]);
    }
}
