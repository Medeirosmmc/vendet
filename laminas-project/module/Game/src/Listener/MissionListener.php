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

    public function __construct(
        MissionService $missionService,
        CombatService $combatService,
        PlayerService $playerService,
        TroopService $troopService,
        BattleReportService $battleReportService
    ) {
        $this->missionService = $missionService;
        $this->combatService = $combatService;
        $this->playerService = $playerService;
        $this->troopService = $troopService;
        $this->battleReportService = $battleReportService;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedManager = $events->getSharedManager();
        $this->listeners[] = $sharedManager->attach(
            'MissionQueueService', // Identifier of the service that triggers the event
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

        // Mission type 1 is attack. We need to confirm this from legacy code.
        if ($mission->mision == 1) {
            $this->handleAttackMission($mission);
        }

        // TODO: Handle other mission types like transport, etc.
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
            // No defender, troops return home
            $this->createReturnMission($mission, $attackingTroops);
            return;
        }

        $defenderId = $defender->id_usuario;
        $defenderBuilding = $this->playerService->getBuildingByCoordinates(
            $mission->coord_dest_1,
            $mission->coord_dest_2,
            $mission->coord_dest_3
        );
        $defenderTroops = $this->troopService->getTroops($defenderBuilding->id_edificio)->toArray();

        // Run combat
        $result = $this->combatService->calculateCombat($attackingTroops, $defenderTroops);

        // Save battle report
        $this->battleReportService->saveReport([
            'atacante' => $attackerId,
            'defensor' => $defenderId,
            'html' => json_encode($result),
        ]);

        // Create return mission for surviving troops
        $survivingTroops = [];
        foreach ($result['remaining'] as $troopName => $data) {
            if ($data['a']['total'] > 0) {
                $survivingTroops[$troopName] = round($data['a']['total']);
            }
        }

        if (!empty($survivingTroops)) {
            $this->createReturnMission($mission, $survivingTroops);
        }

        // TODO: Update defender's troops
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

        $this->missionService->saveMission($returnMission);
    }
}
