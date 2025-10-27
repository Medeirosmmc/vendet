<?php
namespace Game\Service;

use Laminas\Db\Adapter\AdapterInterface;

class CombatService
{
    private $dbAdapter;
    private $troopService;

    public function __construct(AdapterInterface $dbAdapter, TroopService $troopService)
    {
        $this->dbAdapter = $dbAdapter;
        $this->troopService = $troopService;
    }

    public function simulateCombat($attackerId, $defenderId, array $attackingTroops)
    {
        $battleData = [];
        $remainingTroops = [];

        $attackerTroops = $this->troopService->getTroopsData($attackerId, $attackingTroops);
        $defenderTroops = $this->troopService->getTroopsData($defenderId);

        $currentAttackerTroops = $attackerTroops;
        $currentDefenderTroops = $defenderTroops;

        for ($round = 1; $round <= 5; $round++) {
            list($attackerPower, $defenderPower) = $this->calculatePower($currentAttackerTroops, $currentDefenderTroops);

            if ($attackerPower == 0 || $defenderPower == 0) break;

            $attackerLosses = $defenderPower / ($defenderPower + $attackerPower * 2);
            $defenderLosses = $attackerPower / ($attackerPower + $defenderPower * 2);

            $currentAttackerTroops = $this->applyLosses($currentAttackerTroops, $attackerLosses);
            $currentDefenderTroops = $this->applyLosses($currentDefenderTroops, $defenderLosses);

            $battleData[$round] = [
                'attacker_power' => $attackerPower,
                'defender_power' => $defenderPower,
                'attacker_losses_pct' => $attackerLosses,
                'defender_losses_pct' => $defenderLosses,
                'attacker_troops' => $this->getTroopCounts($currentAttackerTroops),
                'defender_troops' => $this->getTroopCounts($currentDefenderTroops),
            ];
        }

        return [
            'rounds' => $battleData,
            'winner' => $this->determineWinner($currentAttackerTroops, $currentDefenderTroops),
        ];
    }

    private function calculatePower($attackerTroops, $defenderTroops)
    {
        $attackerPower = 0;
        foreach ($attackerTroops as $troop) {
            $attackerPower += $troop['quantity'] * $troop['attack'];
        }

        $defenderPower = 0;
        foreach ($defenderTroops as $troop) {
            $defenderPower += $troop['quantity'] * $troop['defense'];
        }

        return [$attackerPower, $defenderPower];
    }

    private function applyLosses($troops, $lossPercentage)
    {
        $remaining = [];
        foreach ($troops as $troop) {
            $lost = floor($troop['quantity'] * $lossPercentage);
            $remainingQuantity = $troop['quantity'] - $lost;
            if ($remainingQuantity > 0) {
                $troop['quantity'] = $remainingQuantity;
                $remaining[] = $troop;
            }
        }
        return $remaining;
    }

    private function getTroopCounts($troops)
    {
        $counts = [];
        foreach ($troops as $troop) {
            $counts[$troop['name']] = $troop['quantity'];
        }
        return $counts;
    }

    private function determineWinner($attackerTroops, $defenderTroops)
    {
        if (empty($attackerTroops)) return 'defender';
        if (empty($defenderTroops)) return 'attacker';
        return 'draw'; // Ou outra lógica para determinar o vencedor em caso de empate
    }
}
