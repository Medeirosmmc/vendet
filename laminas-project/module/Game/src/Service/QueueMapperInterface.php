<?php
namespace Game\Service;

interface QueueMapperInterface
{
    public function getQueue($userId, $buildingId);
    public function addToQueue($userId, $buildingId, QueueableInterface $item);
    public function getFinishedItems();
    public function removeFinishedItems();
}
