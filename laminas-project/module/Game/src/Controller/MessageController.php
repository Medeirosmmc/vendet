<?php
namespace Game\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Game\Service\MessageService;
use Game\Model\Entity\Message;
use Game\Form\MessageForm;
use Game\Service\PlayerService;

class MessageController extends AbstractActionController
{
    protected $messageService;
    protected $playerService;

    public function __construct(MessageService $messageService, PlayerService $playerService)
    {
        $this->messageService = $messageService;
        $this->playerService = $playerService;
    }

    public function indexAction()
    {
        $userId = $this->identity()->id_usuario;
        $messages = $this->messageService->getMessagesForUser($userId);

        return new ViewModel(['messages' => $messages]);
    }

    public function viewAction()
    {
        $messageId = (int) $this->params()->fromRoute('id', 0);
        $message = $this->messageService->getMessageById($messageId);

        return new ViewModel(['message' => $message]);
    }

    public function composeAction()
    {
        $form = new MessageForm();
        $destId = (int) $this->params()->fromQuery('id_dest', 0);
        if ($destId) {
            $destinatario = $this->playerService->getUser($destId);
            $form->get('destinatario')->setValue($destinatario->nombre);
        }

        return new ViewModel(['form' => $form]);
    }

    public function sendAction()
    {
        $form = new MessageForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $destinatario = $this->playerService->getUserByUsername($data['destinatario']);
                $message = new Message([
                    'remitente' => $this->identity()->id_usuario,
                    'destinatario' => $destinatario->id_usuario,
                    'asunto' => $data['asunto'],
                    'mensaje' => $data['mensaje'],
                ]);
                $this->messageService->sendMessage($message);
                return $this->redirect()->toRoute('message');
            }
        }

        $viewModel = new ViewModel(['form' => $form]);
        $viewModel->setTemplate('game/message/compose');
        return $viewModel;
    }

    public function deleteAction()
    {
        $messageId = (int) $this->params()->fromRoute('id', 0);
        $this->messageService->deleteMessage($messageId, $this->identity()->id_usuario);

        return $this->redirect()->toRoute('message');
    }
}
