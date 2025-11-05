<?php
return [
    'buildings' => [
        'almacenAlc' => ['arm' => 200, 'mun' => 200, 'dol' => 0, 'duracion' => 8000, 'puntos' => 7, 'requisitos' => ['cerveceria' => 5]],
        'almacenArm' => ['arm' => 100, 'mun' => 500, 'dol' => 0, 'duracion' => 9000, 'puntos' => 12, 'requisitos' => ['armeria' => 5]],
        'armeria' => ['arm' => 12, 'mun' => 60, 'dol' => 0, 'duracion' => 500, 'puntos' => 2.32, 'requisitos' => []],
        'caja' => ['arm' => 2000, 'mun' => 2000, 'dol' => 1000, 'duracion' => 16000, 'puntos' => 91, 'requisitos' => ['taberna' => 5]],
        'campo' => ['arm' => 1000, 'mun' => 2500, 'dol' => 0, 'duracion' => 5600, 'puntos' => 61, 'requisitos' => []],
        'cerveceria' => ['arm' => 20, 'mun' => 20, 'dol' => 0, 'duracion' => 1000, 'puntos' => 1.6, 'requisitos' => []],
        'contrabando' => ['arm' => 2000, 'mun' => 5000, 'dol' => 500, 'duracion' => 4000, 'puntos' => 136, 'requisitos' => ['oficina' => 5, 'cerveceria' => 8]],
        'deposito' => ['arm' => 500, 'mun' => 600, 'dol' => 0, 'duracion' => 12000, 'puntos' => 18, 'requisitos' => ['municion' => 5]],
        'escuela' => ['arm' => 1000, 'mun' => 1000, 'dol' => 25, 'duracion' => 2000, 'puntos' => 31.75, 'requisitos' => []],
        'minas' => ['arm' => 2000, 'mun' => 2000, 'dol' => 150, 'duracion' => 3000, 'puntos' => 65.5, 'requisitos' => ['oficina' => 5]],
        'municion' => ['arm' => 9, 'mun' => 15, 'dol' => 0, 'duracion' => 600, 'puntos' => 1.39, 'requisitos' => []],
        'oficina' => ['arm' => 100, 'mun' => 200, 'dol' => 0, 'duracion' => 900, 'puntos' => 6, 'requisitos' => []],
        'seguridad' => ['arm' => 900, 'mun' => 1600, 'dol' => 100, 'duracion' => 6000, 'puntos' => 45, 'requisitos' => ['campo' => 2]],
        'taberna' => ['arm' => 10, 'mun' => 50, 'dol' => 0, 'duracion' => 1500, 'puntos' => 2.1, 'requisitos' => ['cerveceria' => 1]],
        'torreta' => ['arm' => 1000, 'mun' => 2000, 'dol' => 200, 'duracion' => 4500, 'puntos' => 57, 'requisitos' => ['oficina' => 5]],
    ],
];
