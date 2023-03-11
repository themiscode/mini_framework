<?php

use App\Core\Application;
use App\Core\Http\Request;
use App\Core\Routing\Route;
use App\Http\Controllers\StaticPageController;

return [
    '/' => Application::make(
        Route::class, 
        StaticPageController::class, 
        'home', 
        Request::GET
    ),
];