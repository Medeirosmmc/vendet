<?php
namespace Game\Service;

use Laminas\Db\Adapter\AdapterInterface;

class TroopService
{
    private $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
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

        $troopAttributes = [
            'soldado' => ['attack' => 10, 'defense' => 10],
            'artillero' => ['attack' => 25, 'defense' => 5],
            'franco' => ['attack' => 50, 'defense' => 2],
            // Adicionar outros tipos de tropa aqui
        ];

        foreach ($userTroops as $troop) {
            if (isset($troopAttributes[$troop->tropa])) {
                if ($troopSelection === null || isset($troopSelection[$troop->tropa])) {
                    $quantity = $troopSelection === null ? $troop->cantidad : $troopSelection[$troop->tropa];
                    $troops[] = [
                        'name' => $troop->tropa,
                        'quantity' => $quantity,
                        'attack' => $troopAttributes[$troop->tropa]['attack'],
                        'defense' => $troopAttributes[$troop->tropa]['defense'],
                    ];
                }
            }
        }

        return $troops;
    }
}
