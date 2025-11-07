<?php
namespace Game\Model\Entity;

use Game\Service\QueueableInterface;

class Mission implements QueueableInterface
{
    public $id_mision;
    public $id_usuario;
    public $mision;
    public $tropas;
    public $cantidad;
    public $recursos_arm;
    public $recursos_mun;
    public $recursos_alc;
    public $recursos_dol;
    public $coord_orig_1;
    public $coord_orig_2;
    public $coord_orig_3;
    public $coord_dest_1;
    public $coord_dest_2;
    public $coord_dest_3;
    public $fecha_inicio;
    public $fecha_fin;
    public $duracion;

    public function __construct(array $data = [])
    {
        $this->exchangeArray($data);
    }

    public function exchangeArray(array $data)
    {
        $this->id_mision      = $data['id_mision'] ?? null;
        $this->id_usuario     = $data['id_usuario'] ?? 0;
        $this->mision         = $data['mision'] ?? 0;
        $this->tropas         = $data['tropas'] ?? '[]';
        $this->cantidad       = $data['cantidad'] ?? 0;
        $this->recursos_arm   = $data['recursos_arm'] ?? 0;
        $this->recursos_mun   = $data['recursos_mun'] ?? 0;
        $this->recursos_alc   = $data['recursos_alc'] ?? 0;
        $this->recursos_dol   = $data['recursos_dol'] ?? 0;
        $this->coord_orig_1   = $data['coord_orig_1'] ?? 0;
        $this->coord_orig_2   = $data['coord_orig_2'] ?? 0;
        $this->coord_orig_3   = $data['coord_orig_3'] ?? 0;
        $this->coord_dest_1   = $data['coord_dest_1'] ?? 0;
        $this->coord_dest_2   = $data['coord_dest_2'] ?? 0;
        $this->coord_dest_3   = $data['coord_dest_3'] ?? 0;
        $this->fecha_inicio   = $data['fecha_inicio'] ?? date('Y-m-d H:i:s');
        $this->fecha_fin      = $data['fecha_fin'] ?? date('Y-m-d H:i:s');
        $this->duracion       = $data['duracion'] ?? 0;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    // Implementation for QueueableInterface
    public function getQueueId()
    {
        return $this->id_mision;
    }

    public function getQueueType()
    {
        return 'mission';
    }

    public function getOwnerId()
    {
        return $this->id_usuario;
    }

    public function getCompletionTime()
    {
        return strtotime($this->fecha_fin);
    }

    public function getQueueCost()
    {
        return [];
    }

    public function getQueueTime()
    {
        return $this->duracion;
    }

    public function getQueueItemName()
    {
        return "Mission #".$this->id_mision;
    }

    public function getQueueItemLevel()
    {
        return $this->mision;
    }

    public function getCoordinates()
    {
        return [$this->coord_dest_1, $this->coord_dest_2, $this->coord_dest_3];
    }
}
