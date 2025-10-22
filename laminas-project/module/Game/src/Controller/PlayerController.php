<?php
namespace Game\Controller;

use Game\Service\PlayerService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class PlayerController extends AbstractActionController
{
    private $playerService;

    public function __construct(PlayerService $playerService)
    {
        $this->playerService = $playerService;
    }

    public function profileAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        $player = $this->playerService->getPlayer($id);

        if (!$player) {
            return $this->notFoundAction();
        }

        return new ViewModel(['player' => $player]);
    }
}
