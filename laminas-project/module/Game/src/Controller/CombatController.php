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
        // Lógica para iniciar um ataque.
        return new ViewModel();
    }
}
