<?php

namespace App\Core\Routing;

use App\Core\Exceptions\RouterException;
use App\Core\Http\Request;

class Router
{
    protected array $routes;
    protected Request $request;

    public function __construct(Request $request) 
    {
        $this->routes = [];
        $this->request = $request;
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