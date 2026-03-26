<?php
namespace Game\Service;

interface QueueableInterface
{
    public function getQueueCost();
    public function getQueueTime();
    public function getQueueItemName();
    public function getQueueItemLevel();
    public function getCoordinates();
}
