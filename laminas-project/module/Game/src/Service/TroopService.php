<?php
namespace Game\Service;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\TableGateway;

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
        $table = new TableGateway('mob_tropas', $this->dbAdapter);
        $rowset = $table->select(['id_usuario' => $userId]);
        return $rowset;
    }

    public function addTroops($userId, array $troopsToAdd)
    {
        $table = new TableGateway('mob_tropas', $this->dbAdapter);
        foreach ($troopsToAdd as $troopName => $quantity) {
            if ($quantity <= 0) continue;

            $userTroop = $table->select(['id_usuario' => $userId, 'tropa' => $troopName])->current();

            if ($userTroop) {
                $newQuantity = $userTroop->cantidad + $quantity;
                $table->update(['cantidad' => $newQuantity], ['id_usuario' => $userId, 'tropa' => $troopName]);
            } else {
                $table->insert(['id_usuario' => $userId, 'tropa' => $troopName, 'cantidad' => $quantity]);
            }
        }
    }

    public function updateTroops($userId, array $troops)
    {
        $table = new TableGateway('mob_tropas', $this->dbAdapter);
        // First, delete all existing troops for the user to ensure a clean slate
        $table->delete(['id_usuario' => $userId]);
        // Now, insert the new troop counts
        foreach ($troops as $troopName => $quantity) {
            if ($quantity > 0) {
                $table->insert(['id_usuario' => $userId, 'tropa' => $troopName, 'cantidad' => $quantity]);
            }
        }
    }

    public function subtractTroops($userId, array $troopsToSubtract)
    {
        $table = new TableGateway('mob_tropas', $this->dbAdapter);
        foreach ($troopsToSubtract as $troopName => $quantity) {
            if ($quantity <= 0) continue;

            $userTroop = $table->select(['id_usuario' => $userId, 'tropa' => $troopName])->current();

            if ($userTroop) {
                $newQuantity = $userTroop->cantidad - $quantity;
                if ($newQuantity < 0) {
                    throw new \Exception("Cannot subtract more troops than available for user $userId, troop $troopName");
                }

                if ($newQuantity == 0) {
                    $table->delete(['id_usuario' => $userId, 'tropa' => $troopName]);
                } else {
                    $table->update(['cantidad' => $newQuantity], ['id_usuario' => $userId, 'tropa' => $troopName]);
                }
            }
        }
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
                    'attack' => $troopDetails['attack'],
                    'defense' => $troopDetails['defense'],
                ],
                'd' => [
                    'total' => $defenderQuantity,
                    'attack' => $troopDetails['attack'],
                    'defense' => $troopDetails['defense'],
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
