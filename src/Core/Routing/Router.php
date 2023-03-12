<?php

namespace App\Core\Routing;

use App\Core\Exceptions\ControllerMethodDoesntExist;
use App\Core\Exceptions\RouteNotFoundException;
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

    public function collectRoutes(string $routesDir)
    {
        if ($dirHandle = opendir($routesDir)) {
            while(($file = readdir($dirHandle)) !== false) {
                if ($file != '.' && $file != '..') {
                    if (!file_exists("$routesDir/$file")) {
                        throw new RouterException("Routes file $file does not exist!");
                    }
        
                    $routes = include "$routesDir/$file";
        
                    $this->routes = array_merge($this->routes, $routes);        
                }
            }

            closedir($dirHandle);
        }
    }

    public function resolveRoute()
    {
        $uri = $this->request->uri();

        if (!isset($this->routes[$uri])) {
            response()->notFound();
        }

        return $this;
    }

    public function executeRequest()
    {
        /** @var \App\Core\Routing\Route */
        $route = $this->routes[$this->request->uri()];

        if ($route->getHttpMethod() != $this->request->method()) {
            response()->methodNotAllowed();
        }

        if (!method_exists($route->getController(), $route->getHandle())) {
            throw new ControllerMethodDoesntExist("Method {$route->getHandle()} does not exist!");
        }

        return $route->getController()->{$route->getHandle()}($this->request);
    }

    public function getRoute($search)
    {
        $routeKey = false;

        foreach ($this->routes as $key => $route) {
            if ($route->getName() == $search) {
                $routeKey = $key;
                break;
            }
        }

        if (!$routeKey) {
            throw new RouteNotFoundException("Route $route not found!");
        }

        return $this->request->getHttpSchema() 
            . $this->request->httpHost() 
            . $routeKey;
    }
}