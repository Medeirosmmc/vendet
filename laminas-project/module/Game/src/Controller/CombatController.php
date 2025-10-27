<?php
namespace Game\Controller;

use Game\Service\CombatService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CombatController extends AbstractActionController
{
    private $combatService;

    public function __construct(CombatService $combatService)
    {
        $this->combatService = $combatService;
    }

    public function indexAction()
    {
        // Lógica para listar relatórios de batalha.
        return new ViewModel();
    }

    public function attackAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $attackerId = $this->identity();
            $defenderId = (int) $data['defender_id'];
            $attackingTroops = $data['troops']; // Espera um array como ['soldado' => 10, 'artillero' => 5]

            $result = $this->combatService->simulateCombat($attackerId, $defenderId, $attackingTroops);

            // TODO: Salvar o relatório da batalha no banco de dados.

            return new ViewModel(['result' => $result]);
        }

        return new ViewModel();
    }
}
