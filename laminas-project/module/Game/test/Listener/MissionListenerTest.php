<?php
namespace GameTest\Listener;

use PHPUnit\Framework\TestCase;
use Laminas\EventManager\Event;
use Game\Listener\MissionListener;
use Game\Service\MissionService;
use Game\Service\CombatService;
use Game\Service\PlayerService;
use Game\Service\TroopService;
use Game\Service\BattleReportService;
use Game\Service\QueueService;
use Game\Model\Entity\Mission;
use Game\Model\Entity\User;
use Game\Model\Entity\Building;

class MissionListenerTest extends TestCase
{
    protected $missionService;
    protected $combatService;
    protected

$playerService;
    protected $troopService;
    protected $battleReportService;
    protected $missionQueueService;
    protected $missionListener;

    protected function setUp(): void
    {
        $this->missionService = $this->createMock(MissionService::class);
        $this->combatService = $this->createMock(CombatService::class);
        $this->playerService = $this->createMock(PlayerService::class);
        $this->troopService = $this->createMock(TroopService::class);
        $this->battleReportService = $this->createMock(BattleReportService::class);
        $this->missionQueueService = $this->createMock(QueueService::class);

        $this->missionListener = new MissionListener(
            $this->missionService,
            $this->combatService,
            $this->playerService,
            $this->troopService,
            $this->battleReportService,
            $this->missionQueueService
        );
    }

    public function testOnMissionCompleteHandlesAttack()
    {
        $mission = new Mission([
            'id_mision' => 1,
            'id_usuario' => 1,
            'mision' => 1, // Attack
            'tropas' => json_encode(['soldado' => 10]),
            'coord_dest_1' => 1, 'coord_dest_2' => 1, 'coord_dest_3' => 2,
            'coord_orig_1' => 1, 'coord_orig_2' => 1, 'coord_orig_3' => 1,
            'duracion' => 60,
        ]);

        $defender = new User(['id_usuario' => 2]);
        $defenderBuilding = new Building(['id_edificio' => 20]);
        $defenderTroops = new \ArrayObject([(object)['tropa' => 'soldado', 'cantidad' => 5]]);

        $this->playerService->method('getUserByBuildingCoordinates')->willReturn($defender);
        $this->playerService->method('getBuildingByCoordinates')->willReturn($defenderBuilding);
        $this->troopService->method('getTroops')->willReturn($defenderTroops);

        $combatResult = [
            'remaining' => [
                'soldado' => ['a' => ['total' => 8], 'd' => ['total' => 2]]
            ]
        ];
        $this->combatService->method('calculateCombat')->willReturn($combatResult);

        $this->battleReportService->expects($this->once())->method('saveReport');
        $this->troopService->expects($this->once())->method('updateTroops')->with(2, ['soldado' => 2]);
        $this->missionService->expects($this->once())->method('saveMission');
        $this->missionQueueService->expects($this->once())->method('addToQueue');

        $event = new Event(null, null, ['item' => $mission]);
        $this->missionListener->onMissionComplete($event);
    }

    public function testOnMissionCompleteHandlesReturn()
    {
        $mission = new Mission([
            'id_mision' => 2,
            'id_usuario' => 1,
            'mision' => 5, // Return
            'tropas' => json_encode(['soldado' => 8]),
        ]);

        $this->troopService->expects($this->once())->method('addTroops')->with(1, ['soldado' => 8]);

        $event = new Event(null, null, ['item' => $mission]);
        $this->missionListener->onMissionComplete($event);
    }
}
