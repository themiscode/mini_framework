<?php

return [
    'database' => [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'name' => 'commerce',
        'port' => 3306,
    ],
    'config' => [
        'path' => root_path() . '/src/config',
    ],
    'router' => [
        'collectRoutes' => [
            'routeFiles' => [
                root_path() . '/src/routes/static_pages.php',
            ],
        ],
    ],
];