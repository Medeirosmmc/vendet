<?php
namespace Game\Controller;

use Game\Service\ConstructionService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ConstructionController extends AbstractActionController
{
    private $constructionService;

    public function __construct(ConstructionService $constructionService)
    {
        $this->constructionService = $constructionService;
    }

    public function indexAction()
    {
        $userId = $this->identity();
        $buildings = $this->constructionService->getBuildings($userId);

        return new ViewModel(['buildings' => $buildings]);
    }
}
