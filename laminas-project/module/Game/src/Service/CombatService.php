<?php
namespace Game\Service;

use Laminas\Db\Adapter\AdapterInterface;

class CombatService
{
    private $dbAdapter;
    private $troopService;

    protected $battleTroops;
    protected $remainingTroops;
    protected $battleData;
    protected $attackerPowerPercentage = 100;
    protected $defenderPowerPercentage = 100;

    public function __construct(AdapterInterface $dbAdapter, TroopService $troopService)
    {
        $this->dbAdapter = $dbAdapter;
        $this->troopService = $troopService;
    }

    public function calculateCombat(array $attackerTroops, array $defenderTroops, array $extra = array())
    {
        if (isset($extra["attackerPowerPercentage"])) $this->attackerPowerPercentage = $extra["attackerPowerPercentage"];
        if (isset($extra["defenderPowerPercentage"])) $this->defenderPowerPercentage = $extra["defenderPowerPercentage"];

        $this->battleTroops = $this->troopService->getCombatData($attackerTroops, $defenderTroops);

        return $this->fight($this->battleTroops);
    }

    protected function fight($battleTroops)
    {
        $this->battleData = array();

        $round = 1;

        $attackerHasTroops = $defenderHasTroops = true;

        while ($round <= 5 && ($attackerHasTroops && $defenderHasTroops)) {

            $power = $this->getPower($battleTroops);

            $this->battleData[$round] = array(
                "defenderAttackPower" => $power["defenderAttackPower"],
                "defenderDefensePower" => $power["defenderDefensePower"],
                "attackerDefensePower" => $power["attackerDefensePower"],
                "attackerAttackPower" => $power["attackerAttackPower"],
                "attackerPowerPercentage" => $this->attackerPowerPercentage,
                "defenderPowerPercentage" => $this->defenderPowerPercentage
            );

            $power["defenderAttackPower"] = $power["defenderAttackPower"] * $this->defenderPowerPercentage / 100;
            $power["attackerAttackPower"] = $power["attackerAttackPower"] * $this->attackerPowerPercentage / 100;

            $lossPercentages = $this->getLossPercentages(
                $power["attackerAttackPower"],
                $power["attackerDefensePower"],
                $power["defenderAttackPower"],
                $power["defenderDefensePower"]
            );

            $attackerHasTroops = $defenderHasTroops = false;

            $this->battleData[$round]["attackerVictoryPercentage"] = round(($power["attackerAttackPower"] + $power["attackerDefensePower"])*100/($power["attackerAttackPower"] + $power["attackerDefensePower"] + $power["defenderAttackPower"] + $power["defenderDefensePower"]));
            $this->battleData[$round]["defenderVictoryPercentage"] = 100 - $this->battleData[$round]["attackerVictoryPercentage"];

            foreach ($battleTroops as $troopName => $data) {

                if (round($data["a"]["total"]) == 0 && round($data["d"]["total"]) == 0) continue;

                $attackerTotal = $data['a']["total"];
                $defenderTotal = $data['d']["total"];

                $attackerDeaths = $attackerTotal * $lossPercentages["attacker"];
                $defenderDeaths = $defenderTotal * $lossPercentages["defender"];

                $this->battleData[$round]["troops"][$troopName] = array("a" => round($attackerTotal), "muertesA" => round($attackerDeaths), "d" => round($defenderTotal), "muertesD" => round($defenderDeaths));

                $battleTroops[$troopName]["a"]["total"] = round($attackerTotal - $attackerDeaths);
                $battleTroops[$troopName]["d"]["total"] = round($defenderTotal - $defenderDeaths);

                $attackerHasTroops = $attackerHasTroops || $battleTroops[$troopName]["a"]["total"] > 0;
                $defenderHasTroops = $defenderHasTroops || round($battleTroops[$troopName]["d"]["total"]) > 0;
            }

            $round++;
        }

       $this->remainingTroops = $battleTroops;

       return [
           'rounds' => $this->battleData,
           'remaining' => $this->remainingTroops
       ];
    }

    protected function getPower(array $battleTroops, $attackingTroop = null, $defendingTroop = null, $modifiers = false)
    {
        $defenderAttackPower = $defenderDefensePower = $attackerAttackPower = $attackerDefensePower = 0;

        foreach ($battleTroops as $troopName => $data) {
              if ($attackingTroop === null || $attackingTroop == $troopName) {
                $attackerDefensePower += $data["a"]["total"] * $data["a"]["defense"];
                $attackerAttackPower += $data["a"]["total"] * $data["a"]["attack"];
              }

              if ($defendingTroop === null || $defendingTroop == $troopName) {
                $defenderDefensePower += $data["d"]["total"] * $data["d"]["defense"];
                $defenderAttackPower += $data["d"]["total"] * $data["d"]["attack"];
              }
        }

        // TODO: Refactor modifiers logic to work with the new service-oriented architecture
        // if ($modifiers) {
        //   $modAt = $this->troopService->getTroop($attackingTroop)->getModificador($defendingTroop);
        //   $attackerAttackPower *= $modAt;
        //   $attackerDefensePower *= $modAt;

        //   $modDef = $this->troopService->getTroop($defendingTroop)->getModificador($attackingTroop);
        //   $defenderAttackPower *= $modDef;
        //   $defenderDefensePower *= $modDef;
        // }

        return array(
            "attackerDefensePower" => $attackerDefensePower,
            "attackerAttackPower" => $attackerAttackPower,
            "defenderDefensePower" => $defenderDefensePower,
            "defenderAttackPower" => $defenderAttackPower
        );
    }

    protected function getLossPercentages($attackerAttackPower, $attackerDefensePower, $defenderAttackPower, $defenderDefensePower)
    {
        if (($defenderAttackPower+$defenderDefensePower) > ($attackerAttackPower+$attackerDefensePower) * 10) {
          $defenderLossPercentage = 0;
          $attackerLossPercentage = 1;
        } elseif (($attackerAttackPower+$attackerDefensePower) > ($defenderAttackPower+$defenderDefensePower) * 10) {
          $attackerLossPercentage = 0;
          $defenderLossPercentage = 1;
        } else {

          $attackerLossPercentage = ($defenderAttackPower + $defenderDefensePower) / ($defenderAttackPower + $defenderDefensePower + ($attackerAttackPower +$attackerDefensePower)*2);
          $defenderLossPercentage = ($attackerAttackPower + $attackerDefensePower) / ($attackerAttackPower + $attackerDefensePower + ($defenderAttackPower +$defenderDefensePower)*2);

          $totalDefense = $defenderAttackPower + $defenderDefensePower;
          $totalAttack = $attackerAttackPower +$attackerDefensePower;
          if ($totalAttack > $totalDefense) {
            $kAt = (($totalAttack*100/$totalDefense)-100)/10;
            $kDef = 0;
          } else {
            $kAt = 0;
            $kDef = (($totalDefense*100/$totalAttack)-100)/10;
          }

          $kAt /= 2;
          $kDef /= 2;

          $attackerLossPercentage = $attackerLossPercentage - $attackerLossPercentage*$kAt/100;
          $defenderLossPercentage = $defenderLossPercentage - $defenderLossPercentage*$kDef/100;
        }
        return array("attacker" => $attackerLossPercentage, "defender" => $defenderLossPercentage);
    }
}
