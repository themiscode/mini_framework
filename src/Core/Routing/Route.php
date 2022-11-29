<?php

namespace App\Core\Routing;

use App\Core\Contracts\Http\ControllerContract;

class Route
{
    private function __construct(
        protected ControllerContract $controller,
        protected string $handle,
        protected string $method
    ) 
    {}

    public function getController() {
        return $this->controller;
    }

    public function getHandle()
    {
        return $this->handle;
    }

    public function getHttpMethod()
    {
        return $this->method;
    }
}