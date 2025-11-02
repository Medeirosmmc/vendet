<?php
namespace Game\Service;

use Laminas\Db\Adapter\AdapterInterface;

class TroopService
{
    private $dbAdapter;
    private $troopConfig;

    public function __construct(AdapterInterface $dbAdapter, array $troopConfig)
    {
        $this->dbAdapter = $dbAdapter;
        $this->troopConfig = $troopConfig;
    }

    public function getTroops($userId)
    {
        $table = new \Laminas\Db\TableGateway\TableGateway('mob_tropas', $this->dbAdapter);
        $rowset = $table->select(['id_usuario' => $userId]);
        return $rowset;
    }

    public function getTroopsData($userId, $troopSelection = null)
    {
        $troops = [];
        $userTroops = $this->getTroops($userId);

        foreach ($userTroops as $troop) {
            if (isset($this->troopConfig[$troop->tropa])) {
                if ($troopSelection === null || isset($troopSelection[$troop->tropa])) {
                    $quantity = $troopSelection === null ? $troop->cantidad : $troopSelection[$troop->tropa];
                    $troops[] = [
                        'name' => $troop->tropa,
                        'quantity' => $quantity,
                        'attack' => $this->troopConfig[$troop->tropa]['attack'],
                        'defense' => $this->troopConfig[$troop->tropa]['defense'],
                    ];
                }
            }
        }

        return $troops;
    }

    public function getCombatData(array $attackerTroops, array $defenderTroops)
    {
        $troopNames = array_unique(array_merge(array_keys($attackerTroops), array_keys($defenderTroops)));
        $combatData = [];

        foreach ($troopNames as $troopName) {
            $attackerQuantity = $attackerTroops[$troopName] ?? 0;
            $defenderQuantity = $defenderTroops[$troopName] ?? 0;

            $troopDetails = $this->getTroop($troopName);

            $combatData[$troopName] = [
                'a' => [
                    'total' => $attackerQuantity,
                    'ataque' => $troopDetails['attack'],
                    'defensa' => $troopDetails['defense'],
                ],
                'd' => [
                    'total' => $defenderQuantity,
                    'ataque' => $troopDetails['attack'],
                    'defensa' => $troopDetails['defense'],
                ],
            ];
        }

        return $combatData;
    }

    public function getTroop($troopName)
    {
        return $this->troopConfig[$troopName] ?? ['attack' => 0, 'defense' => 0];
    }
}
