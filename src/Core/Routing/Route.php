<?php

namespace App\Core\Routing;

use App\Core\Application;

class Route
{
    public function __construct(
        protected string $controller,
        protected string $handle,
        protected string $method,
        protected string $name,
    ) 
    {}

    public function getController() 
    {
        return Application::make($this->controller);
    }

    public function getHandle()
    {
        return $this->handle;
    }

    public function getHttpMethod()
    {
        return $this->method;
    }

    public function getName()
    {
        return $this->name;
    }
}