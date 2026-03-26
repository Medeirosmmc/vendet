<?php
namespace Game\Service;

use Game\Model\Mapper\MissionMapper;
use Game\Model\Entity\Mission;
use Game\Service\PlayerService;
use Game\Service\QueueService;
use Game\Service\TroopService;

class MissionService
{
    protected $missionMapper;
    protected $playerService;
    protected $queueService;
    protected $troopService;

    public function __construct(
        MissionMapper $missionMapper,
        PlayerService $playerService,
        QueueService $queueService,
        TroopService $troopService
    ) {
        $this->missionMapper = $missionMapper;
        $this->playerService = $playerService;
        $this->queueService = $queueService;
        $this->troopService = $troopService;
    }

    public function getMissionsForUser($userId)
    {
        return $this->missionMapper->fetchAll($userId);
    }

    public function saveMission(Mission $mission)
    {
        return $this->missionMapper->save($mission);
    }

    public function createMission(array $data)
    {
        // Basic validation and data preparation
        $originBuilding = $this->playerService->getBuildingById($data['id_edificio_origen']);
        $targetPlayer = $this->playerService->getUserByBuildingCoordinates(
            $data['coord_dest_1'],
            $data['coord_dest_2'],
            $data['coord_dest_3']
        );

        // TODO: Add more validation from legacy Mob_Form_Misiones (vacation mode, resources, capacity)

        $duration = $this->calculateMissionDuration(
            $originBuilding->coord1,
            $originBuilding->coord2,
            $originBuilding->coord3,
            $data['coord_dest_1'],
            $data['coord_dest_2'],
            $data['coord_dest_3']
        );

        $mission = new Mission([
            'id_usuario'     => $data['id_usuario'],
            'mision'         => $data['mision'],
            'tropas'         => json_encode($data['tropas']),
            'recursos_arm'   => $data['recursos_arm'] ?? 0,
            'recursos_mun'   => $data['recursos_mun'] ?? 0,
            'recursos_alc'   => $data['recursos_alc'] ?? 0,
            'recursos_dol'   => $data['recursos_dol'] ?? 0,
            'coord_orig_1'   => $originBuilding->coord1,
            'coord_orig_2'   => $originBuilding->coord2,
            'coord_orig_3'   => $originBuilding->coord3,
            'coord_dest_1'   => $data['coord_dest_1'],
            'coord_dest_2'   => $data['coord_dest_2'],
            'coord_dest_3'   => $data['coord_dest_3'],
            'fecha_inicio'   => date('Y-m-d H:i:s'),
            'fecha_fin'      => date('Y-m-d H:i:s', time() + $duration),
            'duracion'       => $duration,
        ]);

        $missionId = $this->saveMission($mission);
        $mission->id_mision = $missionId;

        // Subtract troops from the origin building
        $this->troopService->subtractTroops($data['id_usuario'], $data['tropas']);

        // TODO: Subtract resources from the origin building

        // Add the mission to the queue for later processing
        $this->queueService->addToQueue($mission->getOwnerId(), null, $mission);

        // TODO: Send a message to the defending player

        return $missionId;
    }

    protected function calculateMissionDuration($x1, $y1, $z1, $x2, $y2, $z2)
    {
        // Simple distance calculation for now.
        // TODO: Replace with a more sophisticated calculation based on troop speed.
        $distance = sqrt(pow($x2 - $x1, 2) + pow($y2 - $y1, 2) + pow($z2 - $z1, 2));
        return (int) ($distance * 60); // 1 minute per unit of distance
    }
}
