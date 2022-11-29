<?php

use App\Core\Contracts\Generic;
use App\Core\References\ParameterReference;
use App\Core\Routing\Router;
use App\Core\Services\ConfigService;
use App\Core\Services\DatabaseService;

return [
    'database' => [
        'class' => DatabaseService::class,
        'arguments' => [
            new ParameterReference('database.host'),
            new ParameterReference('database.user'),
            new ParameterReference('database.pass'),
            new ParameterReference('database.name'),
            new ParameterReference('database.port'),
        ],
    ],
    'config' => [
        'class' => ConfigService::class,
        'arguments' => [
            new ParameterReference('config.path'),
        ],
    ],
    'router' => [
        'class' => Router::class,
        'calls' => [
            [
                'method' => 'collectRoutes',
                'arguments' => [
                    new ParameterReference('router.collectRoutes.routeFiles'),
                ],
            ],
        ],
    ],
];