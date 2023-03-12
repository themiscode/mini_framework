<?php

use App\Core\Application;
use App\Core\Http\Request;
use App\Core\Routing\Route;
use App\Http\Controllers\StaticPageController;

return [
    '/' => Application::make(
        Route::class, 
        StaticPageController::class, // Controller to pass request to
        'home', // Method called inside the controller
        Request::GET, // The request method
        'home' // The route name
    ),
    '/myroute' => Application::make(
        Route::class, 
        StaticPageController::class, // Controller to pass request to
        'myroute', // Method called inside the controller
        Request::GET, // The request method
        'myroute' // The route name
    ),
];