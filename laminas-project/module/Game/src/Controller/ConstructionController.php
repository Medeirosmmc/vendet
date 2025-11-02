<?php
namespace Game\Controller;

use Game\Service\ConstructionService;
use Game\Service\QueueService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ConstructionController extends AbstractActionController
{
    private $constructionService;
    private $queueService;

    public function __construct(ConstructionService $constructionService, QueueService $queueService)
    {
        $this->constructionService = $constructionService;
        $this->queueService = $queueService;
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
}
