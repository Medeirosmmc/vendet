<?php
namespace GameTest\Service;

use PHPUnit\Framework\TestCase;
use Game\Service\MissionService;
use Game\Model\Mapper\MissionMapper;
use Game\Service\PlayerService;
use Game\Service\QueueService;
use Game\Service\TroopService;
use Game\Model\Entity\Mission;
use Game\Model\Entity\Building;
use Game\Model\Entity\User;

class MissionServiceTest extends TestCase
{
    protected $missionMapper;
    protected $playerService;
    protected $queueService;
    protected $troopService;
    protected $missionService;

    protected function setUp(): void
    {
        $this->missionMapper = $this->createMock(MissionMapper::class);
        $this->playerService = $this->createMock(PlayerService::class);
        $this->queueService = $this->createMock(QueueService::class);
        $this->troopService = $this->createMock(TroopService::class);

        $this->missionService = new MissionService(
            $this->missionMapper,
            $this->playerService,
            $this->queueService,
            $this->troopService
        );
    }

    public function testCreateMission()
    {
        $data = [
            'id_usuario' => 1,
            'id_edificio_origen' => 10,
            'mision' => 1,
            'tropas' => ['soldado' => 100],
            'coord_dest_1' => 2,
            'coord_dest_2' => 2,
            'coord_dest_3' => 2,
        ];

        $originBuilding = new Building([
            'id_edificio' => 10,
            'coord1' => 1,
            'coord2' => 1,
            'coord3' => 1,
        ]);

        $targetPlayer = new User(['id_usuario' => 2]);

        $this->playerService->expects($this->once())
            ->method('getBuildingById')
            ->with($data['id_edificio_origen'])
            ->willReturn($originBuilding);

        $this->playerService->expects($this->once())
            ->method('getUserByBuildingCoordinates')
            ->with($data['coord_dest_1'], $data['coord_dest_2'], $data['coord_dest_3'])
            ->willReturn($targetPlayer);

        $this->missionMapper->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Mission::class))
            ->willReturn(99); // Mocked mission ID

        $this->troopService->expects($this->once())
            ->method('subtractTroops')
            ->with($data['id_usuario'], $data['tropas']);

        $this->queueService->expects($this->once())
            ->method('addToQueue')
            ->with($this->equalTo(1), $this->isNull(), $this->callback(function ($mission) {
                return $mission instanceof Mission && $mission->id_mision == 99;
            }));

        $missionId = $this->missionService->createMission($data);

        $this->assertEquals(99, $missionId);
    }
}
