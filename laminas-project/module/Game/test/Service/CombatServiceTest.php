<?php
namespace GameTest\Service;

use Game\Service\CombatService;
use Game\Service\TroopService;
use PHPUnit\Framework\TestCase;
use Laminas\Db\Adapter\AdapterInterface;

class CombatServiceTest extends TestCase
{
    public function testCalculateCombat()
    {
        $attackerTroops = ['soldado' => 100];
        $defenderTroops = ['soldado' => 80];

        $troopServiceMock = $this->createMock(TroopService::class);
        $troopServiceMock->method('getCombatData')->willReturn([
            'soldado' => [
                'a' => ['total' => 100, 'attack' => 10, 'defense' => 10],
                'd' => ['total' => 80, 'attack' => 10, 'defense' => 10],
            ],
        ]);

        $dbAdapterMock = $this->createMock(AdapterInterface::class);

        $combatService = new CombatService($dbAdapterMock, $troopServiceMock);
        $result = $combatService->calculateCombat($attackerTroops, $defenderTroops);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('rounds', $result);
        $this->assertArrayHasKey('remaining', $result);

        $this->assertCount(5, $result['rounds']);
        $this->assertEquals(36, $result['remaining']['soldado']['a']['total']);
        $this->assertEquals(1, $result['remaining']['soldado']['d']['total']);
    }
}
