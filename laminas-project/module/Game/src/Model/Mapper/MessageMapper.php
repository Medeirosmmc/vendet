<?php
namespace Game\Model\Mapper;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql\Select;
use Game\Model\Entity\Message;

class MessageMapper
{
    protected $adapter;
    protected $tableGateway;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->tableGateway = new TableGateway('mob_mensajes', $this->adapter);
    }

    public function fetchAll($userId, $folder = 0)
    {
        $select = new Select($this->tableGateway->getTable());
        $select->where(['destinatario' => $userId, 'borrado_dest' => 0, 'id_carpeta' => $folder]);
        $select->order('fecha_enviado DESC');

        $resultSet = $this->tableGateway->selectWith($select);

        $messages = [];
        foreach ($resultSet as $row) {
            $messages[] = new Message((array)$row);
        }
        return $messages;
    }

    public function get($messageId)
    {
        $resultSet = $this->tableGateway->select(['id_mensaje' => $messageId]);

        return new Message((array)$resultSet->current());
    }

    public function save(Message $message)
    {
        $data = $message->getArrayCopy();
        unset($data['id_mensaje']);

        $this->tableGateway->insert($data);

        return $this->tableGateway->getLastInsertValue();
    }

    public function delete($messageId, $userId)
    {
        $message = $this->get($messageId);

        if ($message->destinatario == $userId) {
            $this->tableGateway->update(['borrado_dest' => 1], ['id_mensaje' => $messageId]);
        } elseif ($message->remitente == $userId) {
            $this->tableGateway->update(['borrado_rem' => 1], ['id_mensaje' => $messageId]);
        }
    }
}
