<?php

namespace App\Core\Routing;

use App\Core\Exceptions\RouterException;

class Router
{
    public const GET = 'GET';
    public const POST = 'POST';

    protected array $routes;

    public function __construct() {
        $this->routes = [];
    }

    public function collectRoutes(array $routeFiles)
    {
        foreach ($routeFiles as $file) {
            if (!file_exists($file)) {
                throw new RouterException("Routes file $file does not exist!");
            }

            $routes = include $file;

            $this->routes = array_merge($this->routes, $routes);
        }
    }

    public function resolveRoute()
    {

    }

    public function executeRequest()
    {
        
    }
}