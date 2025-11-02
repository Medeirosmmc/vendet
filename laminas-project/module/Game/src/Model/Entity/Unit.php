<?php
namespace Game\Model\Entity;

use Game\Service\QueueableInterface;

class Unit implements QueueableInterface
{
    private $name;
    private $level;
    private $coordinates;
    private $cost;
    private $time;

    public function __construct($name, $level, $coordinates, $cost, $time)
    {
        $this->name = $name;
        $this->level = $level;
        $this->coordinates = $coordinates;
        $this->cost = $cost;
        $this->time = $time;
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
        return $this->name;
    }

    public function getQueueItemLevel()
    {
        return $this->level;
    }

    public function getCoordinates()
    {
        return $this->coordinates;
    }
}
