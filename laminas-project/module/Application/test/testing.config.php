<?php

return [
    'modules' => [
        'Laminas\\Session',
        'Laminas\\Router',
        'Laminas\\Validator',
        'Application',
    ],
    'module_listener_options' => [
        'config_glob_paths' => [
            realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ],
];
