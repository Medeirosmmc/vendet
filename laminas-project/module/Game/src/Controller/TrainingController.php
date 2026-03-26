<?php
namespace Game\Controller;

use Game\Service\TrainingService;
use Game\Service\QueueService;
use Game\Service\TrainingDataService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class TrainingController extends AbstractActionController
{
    private $trainingService;
    private $queueService;
    private $trainingDataService;

    public function __construct(
        TrainingService $trainingService,
        QueueService $queueService,
        TrainingDataService $trainingDataService
    ) {
        $this->trainingService = $trainingService;
        $this->queueService = $queueService;
        $this->trainingDataService = $trainingDataService;
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

        $trainingDetails = $this->trainingService->getTraining($userId, $unitName);
        $currentLevel = $trainingDetails[$unitName];
        $schoolLevel = $this->trainingService->getSchoolLevel($buildingId);

        $cost = $this->trainingDataService->getCost($unitName, $currentLevel);
        $time = $this->trainingDataService->getTime($unitName, $currentLevel, $schoolLevel);
        $coordinates = '1:1:1'; // placeholder

        $unit = new \Game\Model\Entity\Unit($unitName, $currentLevel + 1, $coordinates, $cost, $time);

        $this->queueService->addToQueue($userId, $buildingId, $unit);

        return $this->redirect()->toRoute('training', ['action' => 'queue', 'id' => $buildingId]);
    }
}
