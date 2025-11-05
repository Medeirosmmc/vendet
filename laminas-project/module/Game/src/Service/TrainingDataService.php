<?php
namespace Game\Service;

class TrainingDataService
{
    private $trainingConfig;

    public function __construct(array $trainingConfig)
    {
        $this->trainingConfig = $trainingConfig;
    }

    public function getTrainingData($trainingName)
    {
        return $this->trainingConfig[$trainingName] ?? null;
    }

    public function getCost($trainingName, $level)
    {
        $trainingData = $this->getTrainingData($trainingName);
        if (!$trainingData) {
            return null;
        }

        return [
            'arm' => $trainingData['arm'] * ($level + 1) * ($level + 1),
            'mun' => $trainingData['mun'] * ($level + 1) * ($level + 1),
            'dol' => $trainingData['dol'] * ($level + 1) * ($level + 1),
        ];
    }

    public function getTime($trainingName, $level, $schoolLevel)
    {
        $trainingData = $this->getTrainingData($trainingName);
        if (!$trainingData) {
            return null;
        }

        $schoolLevel = max(1, $schoolLevel);
        $seconds = (pow($level + 1, 2) * $trainingData['duracion']) / $schoolLevel;
        return round($seconds / 3);
    }
}
