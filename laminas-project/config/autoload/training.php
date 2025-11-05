<?php
return [
    'training' => [
        'administracion' => ['arm' => 0, 'mun' => 0, 'dol' => 5000, 'duracion' => 14400, 'puntos' => 76, 'requisitos' => ['seguridad' => 5]],
        'armas' => ['arm' => 1000, 'mun' => 200, 'dol' => 3000, 'duracion' => 5100, 'puntos' => 53, 'requisitos' => ['extorsion' => 5]],
        'combate' => ['arm' => 2000, 'mun' => 2000, 'dol' => 3000, 'duracion' => 6200, 'puntos' => 76, 'requisitos' => ['extorsion' => 3]],
        'contrabando' => ['arm' => 0, 'mun' => 0, 'dol' => 1500, 'duracion' => 9600, 'puntos' => 23.5, 'requisitos' => ['encargos' => 1]],
        'encargos' => ['arm' => 1000, 'mun' => 2500, 'dol' => 1000, 'duracion' => 5000, 'puntos' => 46, 'requisitos' => ['rutas' => 4]],
        'espionaje' => ['arm' => 500, 'mun' => 500, 'dol' => 300, 'duracion' => 4200, 'puntos' => 13, 'requisitos' => []],
        'explosivos' => ['arm' => 10000, 'mun' => 19500, 'dol' => 15000, 'duracion' => 42000, 'puntos' => 471, 'requisitos' => ['encargos' => 4, 'combate' => 4]],
        'extorsion' => ['arm' => 1000, 'mun' => 2000, 'dol' => 0, 'duracion' => 3000, 'puntos' => 26, 'requisitos' => []],
        'guerrilla' => ['arm' => 8000, 'mun' => 10000, 'dol' => 12000, 'duracion' => 20000, 'puntos' => 321, 'requisitos' => ['seguridad' => 6, 'tiro' => 6]],
        'honor' => ['arm' => 0, 'mun' => 0, 'dol' => 280000, 'duracion' => 92000, 'puntos' => 4201, 'requisitos' => ['espionaje' => 8, 'contrabando' => 8]],
        'proteccion' => ['arm' => 3000, 'mun' => 5000, 'dol' => 2000, 'duracion' => 5000, 'puntos' => 96, 'requisitos' => ['seguridad' => 4]],
        'psicologico' => ['arm' => 2000, 'mun' => 5000, 'dol' => 16000, 'duracion' => 26000, 'puntos' => 301, 'requisitos' => ['guerrilla' => 4]],
        'quimico' => ['arm' => 4000, 'mun' => 12000, 'dol' => 10000, 'duracion' => 14400, 'puntos' => 291, 'requisitos' => ['explosivos' => 4, 'psicologico' => 4]],
        'rutas' => ['arm' => 500, 'mun' => 1200, 'dol' => 0, 'duracion' => 2000, 'puntos' => 15.5, 'requisitos' => []],
        'seguridad' => ['arm' => 1000, 'mun' => 4000, 'dol' => 1000, 'duracion' => 4000, 'puntos' => 61, 'requisitos' => []],
        'tiro' => ['arm' => 5000, 'mun' => 12000, 'dol' => 10000, 'duracion' => 19200, 'puntos' => 296, 'requisitos' => ['armas' => 4, 'proteccion' => 4]],
    ],
];
