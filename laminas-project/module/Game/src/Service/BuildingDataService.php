<?php
namespace Game\Service;

class BuildingDataService
{
    private $buildingConfig;

    public function __construct(array $buildingConfig)
    {
        $this->buildingConfig = $buildingConfig;
    }

    public function getBuildingData($buildingName)
    {
        return $this->buildingConfig[$buildingName] ?? null;
    }

    public function getCost($buildingName, $level)
    {
        $buildingData = $this->getBuildingData($buildingName);
        if (!$buildingData) {
            return null;
        }

        return [
            'arm' => $buildingData['arm'] * ($level + 1) * ($level + 1),
            'mun' => $buildingData['mun'] * ($level + 1) * ($level + 1),
            'dol' => $buildingData['dol'] * ($level + 1) * ($level + 1),
        ];
    }

    public function getTime($buildingName, $level, $officeLevel)
    {
        $buildingData = $this->getBuildingData($buildingName);
        if (!$buildingData) {
            return null;
        }

        $officeLevel = max(1, $officeLevel);
        $seconds = (pow($level + 1, 2) * $buildingData['duracion']) / $officeLevel;
        return round($seconds / 3);
    }
}
