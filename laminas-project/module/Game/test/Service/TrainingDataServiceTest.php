<?php
namespace GameTest\Service;

use Game\Service\TrainingDataService;
use PHPUnit\Framework\TestCase;

class TrainingDataServiceTest extends TestCase
{
    private $trainingConfig = [
        'combate' => ['arm' => 2000, 'mun' => 2000, 'dol' => 3000, 'duracion' => 6200, 'puntos' => 76, 'requisitos' => ['extorsion' => 3]],
    ];

    public function testGetCost()
    {
        $trainingDataService = new TrainingDataService($this->trainingConfig);
        $cost = $trainingDataService->getCost('combate', 0);
        $this->assertEquals(['arm' => 2000, 'mun' => 2000, 'dol' => 3000], $cost);

        $cost = $trainingDataService->getCost('combate', 1);
        $this->assertEquals(['arm' => 8000, 'mun' => 8000, 'dol' => 12000], $cost);
    }

    public function testGetTime()
    {
        $trainingDataService = new TrainingDataService($this->trainingConfig);
        $time = $trainingDataService->getTime('combate', 0, 1);
        $this->assertEquals(2067, $time);

        $time = $trainingDataService->getTime('combate', 1, 2);
        $this->assertEquals(4133, $time);
    }
}
