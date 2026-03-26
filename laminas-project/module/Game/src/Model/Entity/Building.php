<?php
namespace Game\Model\Entity;

use Game\Service\QueueableInterface;

class Building //implements QueueableInterface
{
    public $id_edificio;
    public $id_usuario;
    public $nombre;
    public $nivel;
    public $coord1;
    public $coord2;
    public $coord3;

    // Properties for QueueableInterface, can be populated as needed
    public $cost;
    public $time;

    public function __construct(array $data = [])
    {
        $this->exchangeArray($data);
    }

    public function exchangeArray(array $data)
    {
        $this->id_edificio = $data['id_edificio'] ?? null;
        $this->id_usuario  = $data['id_usuario'] ?? null;
        $this->nombre      = $data['nombre'] ?? null;
        $this->nivel       = $data['nivel'] ?? 0;
        $this->coord1      = $data['coord1'] ?? 0;
        $this->coord2      = $data['coord2'] ?? 0;
        $this->coord3      = $data['coord3'] ?? 0;
        $this->cost        = $data['cost'] ?? [];
        $this->time        = $data['time'] ?? 0;
    }

    public function getQueueCost()
    {
        return $this->cost;
    }

    public function getQueueTime()
    {
        return $this->time;
    }

    public function getQueueItemName()
    {
        return $this->nombre;
    }

    public function getQueueItemLevel()
    {
        return $this->nivel;
    }

    public function getCoordinates()
    {
        return [$this->coord1, $this->coord2, $this->coord3];
    }
}
