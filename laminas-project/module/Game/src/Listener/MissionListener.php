<?php
namespace Game\Listener;

use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;
use Game\Service\QueueService;
use Game\Service\MissionService;
use Game\Service\CombatService;
use Game\Service\PlayerService;
use Game\Service\TroopService;
use Game\Service\BattleReportService;
use Game\Model\Entity\Mission;

class MissionListener extends AbstractListenerAggregate
{
    protected $missionService;
    protected $combatService;
    protected $playerService;
    protected $troopService;
    protected $battleReportService;
    protected $missionQueueService;

    public function __construct(
        MissionService $missionService,
        CombatService $combatService,
        PlayerService $playerService,
        TroopService $troopService,
        BattleReportService $battleReportService,
        QueueService $missionQueueService
    ) {
        $this->missionService = $missionService;
        $this->combatService = $combatService;
        $this->playerService = $playerService;
        $this->troopService = $troopService;
        $this->battleReportService = $battleReportService;
        $this->missionQueueService = $missionQueueService;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedManager = $events->getSharedManager();
        $this->listeners[] = $sharedManager->attach(
            'MissionQueueService',
            QueueService::EVENT_ITEM_PROCESSED,
            [$this, 'onMissionComplete'],
            $priority
        );
    }

    public function onMissionComplete($event)
    {
        $mission = $event->getParam('item');

        if (!$mission instanceof Mission) {
            return;
        }

        // Mission type 1 is attack
        if ($mission->mision == 1) {
            $this->handleAttackMission($mission);
        }
        // Mission type 5 is returning
        elseif ($mission->mision == 5) {
            $this->handleReturnMission($mission);
        }
    }

    protected function handleAttackMission(Mission $mission)
    {
        $attackerId = $mission->id_usuario;
        $attackingTroops = json_decode($mission->tropas, true);

        $defender = $this->playerService->getUserByBuildingCoordinates(
            $mission->coord_dest_1,
            $mission->coord_dest_2,
            $mission->coord_dest_3
        );

        if (!$defender) {
            $this->createReturnMission($mission, $attackingTroops);
            return;
        }

        $defenderId = $defender->id_usuario;
        $defenderBuilding = $this->playerService->getBuildingByCoordinates(
            $mission->coord_dest_1,
            $mission->coord_dest_2,
            $mission->coord_dest_3
        );

        $defenderTroopsResult = $this->troopService->getTroops($defenderBuilding->id_edificio);
        $defenderTroops = [];
        foreach ($defenderTroopsResult as $troop) {
            $defenderTroops[$troop->tropa] = $troop->cantidad;
        }

        $result = $this->combatService->calculateCombat($attackingTroops, $defenderTroops);

        $this->battleReportService->saveReport([
            'atacante' => $attackerId,
            'defensor' => $defenderId,
            'html' => json_encode($result),
        ]);

        $survivingAttackers = [];
        foreach ($result['remaining'] as $troopName => $data) {
            if (isset($data['a']['total']) && $data['a']['total'] > 0) {
                $survivingAttackers[$troopName] = round($data['a']['total']);
            }
        }

        if (!empty($survivingAttackers)) {
            $this->createReturnMission($mission, $survivingAttackers);
        }

        $survivingDefenders = [];
        foreach ($result['remaining'] as $troopName => $data) {
            if (isset($data['d']['total']) && $data['d']['total'] > 0) {
                $survivingDefenders[$troopName] = round($data['d']['total']);
            }
        }

        $this->troopService->updateTroops($defenderId, $survivingDefenders);
    }

    protected function handleReturnMission(Mission $mission)
    {
        $returningTroops = json_decode($mission->tropas, true);
        if (!empty($returningTroops)) {
            $this->troopService->addTroops($mission->id_usuario, $returningTroops);
        }
    }

    protected function createReturnMission(Mission $originalMission, array $troops)
    {
        $returnMission = new Mission([
            'id_usuario'     => $originalMission->id_usuario,
            'mision'         => 5, // 5 = returning
            'tropas'         => json_encode($troops),
            'coord_orig_1'   => $originalMission->coord_dest_1,
            'coord_orig_2'   => $originalMission->coord_dest_2,
            'coord_orig_3'   => $originalMission->coord_dest_3,
            'coord_dest_1'   => $originalMission->coord_orig_1,
            'coord_dest_2'   => $originalMission->coord_orig_2,
            'coord_dest_3'   => $originalMission->coord_orig_3,
            'fecha_inicio'   => date('Y-m-d H:i:s'),
            'fecha_fin'      => date('Y-m-d H:i:s', time() + $originalMission->duracion),
            'duracion'       => $originalMission->duracion,
        ]);

        $missionId = $this->missionService->saveMission($returnMission);
        $returnMission->id_mision = $missionId;

        $this->missionQueueService->addToQueue($returnMission->getOwnerId(), null, $returnMission);
    }
}
