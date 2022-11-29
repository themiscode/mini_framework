<?php

use App\Core\Routing\Router;

return [
    '/' => [
        'method' => Router::GET,
        'handle' => [
            'controller' => StaticPageController::class, 
            'call' => 'home',
        ],
    ],
];