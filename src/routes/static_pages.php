<?php

use App\Core\Application;
use App\Core\Routing\Route;
use App\Core\Routing\Router;
use App\Http\Controllers\StaticPageController;

return [
    '/' => Application::make(
        Route::class, 
        StaticPageController::class, 
        'home', 
        Router::GET
    ),
];