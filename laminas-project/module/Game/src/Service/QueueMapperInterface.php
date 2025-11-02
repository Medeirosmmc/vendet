<?php
namespace Game\Service;

interface QueueMapperInterface
{
    public function getQueue($userId, $buildingId);
}
