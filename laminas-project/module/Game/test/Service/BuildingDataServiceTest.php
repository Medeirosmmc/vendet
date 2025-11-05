<?php
namespace GameTest\Service;

use Game\Service\BuildingDataService;
use PHPUnit\Framework\TestCase;

class BuildingDataServiceTest extends TestCase
{
    private $buildingConfig = [
        'oficina' => ['arm' => 100, 'mun' => 200, 'dol' => 0, 'duracion' => 900, 'puntos' => 6, 'requisitos' => []],
    ];

    public function testGetCost()
    {
        $buildingDataService = new BuildingDataService($this->buildingConfig);
        $cost = $buildingDataService->getCost('oficina', 0);
        $this->assertEquals(['arm' => 100, 'mun' => 200, 'dol' => 0], $cost);

        $cost = $buildingDataService->getCost('oficina', 1);
        $this->assertEquals(['arm' => 400, 'mun' => 800, 'dol' => 0], $cost);
    }

    public function testGetTime()
    {
        $buildingDataService = new BuildingDataService($this->buildingConfig);
        $time = $buildingDataService->getTime('oficina', 0, 1);
        $this->assertEquals(300, $time);

        $time = $buildingDataService->getTime('oficina', 1, 2);
        $this->assertEquals(600, $time);
    }
}
