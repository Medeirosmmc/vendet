<?php
namespace Game\Service;

interface QueueableInterface
{
    public function getQueueCost();
    public function getQueueTime();
}
