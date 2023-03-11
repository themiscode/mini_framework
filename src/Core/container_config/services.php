<?php

use App\Core\Http\Request;
use App\Core\References\ParameterReference;
use App\Core\References\ServiceReference;
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
    'request' => [
        'class' => Request::class,
    ],
    'router' => [
        'class' => Router::class,
        'arguments' => [
            new ServiceReference('request'),
        ],
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