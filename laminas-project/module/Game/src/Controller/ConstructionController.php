<?php
namespace Game\Controller;

use Game\Service\ConstructionService;
use Game\Service\QueueService;
use Game\Service\BuildingDataService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ConstructionController extends AbstractActionController
{
    private $constructionService;
    private $queueService;
    private $buildingDataService;

    public function __construct(
        ConstructionService $constructionService,
        QueueService $queueService,
        BuildingDataService $buildingDataService
    ) {
        $this->constructionService = $constructionService;
        $this->queueService = $queueService;
        $this->buildingDataService = $buildingDataService;
    }

    public function indexAction()
    {
        $userId = $this->identity()->id_usuario;
        $buildings = $this->constructionService->getBuildings($userId);

        return new ViewModel(['buildings' => $buildings]);
    }

    public function queueAction()
    {
        $userId = $this->identity()->id_usuario;
        $buildingId = (int) $this->params()->fromRoute('id', 0);
        $queue = $this->queueService->getQueue($userId, $buildingId);

        return new ViewModel(['queue' => $queue]);
    }

    public function addAction()
    {
        $userId = $this->identity()->id_usuario;
        $buildingId = (int) $this->params()->fromRoute('id', 0);
        $buildingName = $this->params()->fromRoute('building');

        $buildingDetails = $this->constructionService->getBuilding($buildingId);
        $currentLevel = $buildingDetails[$buildingName];
        $officeLevel = $buildingDetails['oficina'];

        $cost = $this->buildingDataService->getCost($buildingName, $currentLevel);
        $time = $this->buildingDataService->getTime($buildingName, $currentLevel, $officeLevel);
        $coordinates = $buildingDetails['coord1'] . ':' . $buildingDetails['coord2'] . ':' . $buildingDetails['coord3'];

        $building = new \Game\Model\Entity\Building($buildingName, $currentLevel + 1, $coordinates, $cost, $time);

        $this->queueService->addToQueue($userId, $buildingId, $building);

        return $this->redirect()->toRoute('construction', ['action' => 'queue', 'id' => $buildingId]);
    }
}
