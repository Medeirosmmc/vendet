<?php
namespace Game\Controller;

use Game\Service\TrainingService;
use Game\Service\QueueService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class TrainingController extends AbstractActionController
{
    private $trainingService;
    private $queueService;

    public function __construct(TrainingService $trainingService, QueueService $queueService)
    {
        $this->trainingService = $trainingService;
        $this->queueService = $queueService;
    }

    public function indexAction()
    {
        $userId = $this->identity()->id_usuario;
        $trainingQueue = $this->trainingService->getTrainingQueue($userId);

        return new ViewModel(['trainingQueue' => $trainingQueue]);
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
        $unitName = $this->params()->fromRoute('unit');

        // TODO: Get unit details from a service
        $unit = new \Game\Model\Entity\Unit($unitName, 1, '1:1:1', ['arm' => 10, 'mun' => 10, 'dol' => 10], 60);

        $this->queueService->addToQueue($userId, $buildingId, $unit);

        return $this->redirect()->toRoute('training', ['action' => 'queue', 'id' => $buildingId]);
    }
}
