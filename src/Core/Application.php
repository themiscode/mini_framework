<?php

namespace App\Core;

class Application 
{
    private static ?Application $instance = null;

    protected Container $container;

    protected bool $started = false;

    protected array $bindings = [];

    /**
     * Creates a new application instance.
     */
    private function __construct() {}

    /**
     * Creates a new application instance or returns the existing
     * instance.
     *
     * @return \App\Core\Application the application instance
     */
    public static function getInstance() 
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Retruns the application container.
     *
     * @return \App\Core\Container the container instance.
     */
    public function getApplicationContainer()
    {
        return $this->container;
    }

    /**
     * Set the binding for an interface to its implemention class.
     *
     * @param string $interface
     * @param string $implementation
     *
     * @return this
     */
    public function bind(string $interface, string $implementation)
    {
        $this->bindings[$interface] = $implementation;

        return $this;
    }

    /**
     * Starts the application.
     *
     * @return void
     */
    public function start()
    {
        if ($this->started) {
            return;
        }

        $services = include root_path() . '/src/Core/container_config/services.php';
        $parameters = include root_path() . '/src/Core/container_config/parameters.php';

        $this->container = new Container(
            $services, 
            $parameters, 
            $this->bindings, 
        );

        $this->started = true;

        return $this;
    }

    /**
     * Instanciate a new concrete class.
     *
     * @param string $concrete
     * @param mixed $args
     *
     * @return Object the instance of the class
     */
    public static function make(string $concrete, mixed ...$args)
    {
        $reflector = new \ReflectionClass($concrete);

        $instance = $reflector->newInstanceArgs($args);

        return $instance;
    }
}