<?php
namespace Game\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Game\Service\MissionService;
use Game\Service\TroopService;

class MissionController extends AbstractActionController
{
    protected $missionService;
    protected $troopService;

    public function __construct(MissionService $missionService, TroopService $troopService)
    {
        $this->missionService = $missionService;
        $this->troopService = $troopService;
    }

    public function indexAction()
    {
        $userId = $this->identity()->id_usuario;
        $troops = $this->troopService->getTroops($userId);

        // We will need a form here, but for now let's pass the troops
        return new ViewModel([
            'troops' => $troops
        ]);
    }

    public function createAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->redirect()->toRoute('mission');
        }

        $data = $request->getPost()->toArray();
        $data['id_usuario'] = $this->identity()->id_usuario;
        // This is a placeholder for the origin building, which should be selectable
        $data['id_edificio_origen'] = $this->identity()->id_edificio_actual;

        // Troops would be an array of troop_name => quantity
        // $data['tropas'] = ...

        $this->missionService->createMission($data);

        return $this->redirect()->toRoute('mission');
    }
}
