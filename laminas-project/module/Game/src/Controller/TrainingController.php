<?php
namespace Game\Controller;

use Game\Service\TrainingService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class TrainingController extends AbstractActionController
{
    private $trainingService;

    public function __construct(TrainingService $trainingService)
    {
        $this->trainingService = $trainingService;
    }

    public function indexAction()
    {
        $userId = $this->identity();
        $trainingQueue = $this->trainingService->getTrainingQueue($userId);

        return new ViewModel(['trainingQueue' => $trainingQueue]);
    }
}
