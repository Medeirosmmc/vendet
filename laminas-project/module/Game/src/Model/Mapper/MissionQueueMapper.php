<?php
namespace Game\Model\Mapper;

use Laminas\Db\Adapter\AdapterInterface;
use Game\Service\QueueMapperInterface;
use Game\Model\Entity\Mission;

class MissionQueueMapper implements QueueMapperInterface

{
    protected $adapter;
    protected $missionMapper;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->missionMapper = new MissionMapper($adapter);
    }

    public function fetchQueueItems($limit = 100)
    {
        // This logic will be handled by the ProcessQueueCommand,
        // which should fetch missions with fecha_fin <= NOW()
        // For now, this mapper connects the Mission entity to the QueueService
        return [];
    }

    public function get($id)
    {
        return $this->missionMapper->get($id);
    }
}
