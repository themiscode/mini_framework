<?php

namespace App\Core;

use App\Core\Exceptions\ContainerException;
use App\Core\Exceptions\ParameterNotFoundException;
use App\Core\Exceptions\ServiceNotFoundException;
use App\Core\References\ParameterReference;
use App\Core\References\ServiceReference;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface 
{
    /**
     * The container's service definitions.
     *
     * @var array
     */
    private array $services;

    /**
     * The container's parameter definitions.
     *
     * @var array
     */
    private array $parameters;

    /**
     * The contract - class bindings.
     *
     * @var array
     */
    private array $bindings;

    /**
     * The array that stores all the resolved services.
     *
     * @var array
     */
    private array $serviceStore;

    public function __construct(
        array $services = [], 
        array $parameters = [], 
        array $bindings = [],
    )
    {
        $this->services = $services;
        $this->parameters = $parameters;
        $this->bindings = $bindings;
        
        $this->serviceStore = [];
    }

    /**
     * Returns a service if it exists.
     *
     * @param string $id
     *
     * @return Object
     * 
     * @throws ServiceNotFoundException
     */
    public function get(string $id) 
    { 
        if (!$this->has($id)) {
            throw new ServiceNotFoundException("Service $id not found!");
        }

        if (!isset($this->serviceStore[$id])) {
            $this->serviceStore[$id] = $this->createService($id);
        }

        return $this->serviceStore[$id];
    }

    /**
     * Checks wether service exists or not.
     *
     * @param string $id
     *
     * @return boolean
     */
    public function has(string $id): bool 
    { 
        return isset($this->services[$id]);
    }

    /**
     * Creates the given service and returns it.
     *
     * @param string $id
     *
     * @return Object
     * 
     * @throws ContainerException
     */
    protected function createService(string $id)
    {
        $entry = &$this->services[$id];

        if (!is_array($entry) || !isset($entry['class'])) {
            throw new ContainerException("Service entry $id must be an array containing a 'class' key!");
        }
        elseif (!class_exists($entry['class']) && !interface_exists($entry['class'])) {
            throw new ContainerException("Service class $id does not exist: {$entry['class']}");
        }
        elseif (isset($entry['lock'])) {
            throw new ContainerException("Service $id contains a circular reference!");
        }

        $entry['lock'] = true;
        
        $arguments = isset($entry['arguments']) ? $this->resolveArguments($entry['arguments']) : [];

        $reflector = new \ReflectionClass($entry['class']);

        if ($reflector->isInterface()) {
            if (!isset($this->bindings[$entry['class']])) {
                throw new ContainerException("Interface {$entry['class']} was declared in services without an implementation set in bindings!");
            }

            $reflector = new \ReflectionClass($this->bindings[$entry['class']]);
        }

        $service = $reflector->newInstanceArgs($arguments);

        if (isset($entry['calls'])) {
            $this->initializeService($service, $id, $entry['calls']);
        }

        return $service;
    }

    /**
     * Returns a parameter using dot notation to find it.
     * E.g. 'field.subfield' is equal to $array['field']['subfield'].
     * 
     * If it is not found it throws an exception.
     *
     * @param string $name
     *
     * @return mixed
     * 
     * @throws ParameterNotFoundException
     */
    public function getParameter(string $name)
    {
        $tokens = explode('.', $name);

        $context = $this->parameters;

        while (($token = array_shift($tokens)) !== null) {
            if (!isset($context[$token])) {
                throw new ParameterNotFoundException("Parameter $name not found!");
            }

            $context = $context[$token];
        }

        return $context;
    }

    /**
     * Converts an array of argument definitions into 
     * an array of argument values and returns it.
     *
     * @param array $argumentDefinitions
     *
     * @return array
     */
    protected function resolveArguments(array $argumentDefinitions)
    {
        $arguments = [];

        foreach ($argumentDefinitions as $argumentDefinition) {
            if ($argumentDefinition instanceof ServiceReference) {
                $argumentServiceName = $argumentDefinition->getName();

                $arguments[] = $this->get($argumentServiceName);
            }
            elseif ($argumentDefinition instanceof ParameterReference) {
                $argumentParameterName = $argumentDefinition->getName();

                $arguments[] = $this->getParameter($argumentParameterName);
            }
            else {
                $arguments[] = $argumentDefinition;
            }
        }

        return $arguments;
    }

    /**
     * Initialize the service using the call definitions.
     *
     * @param Object $service
     * @param string $id
     * @param array $callDefinitions
     *
     * @return void
     */
    protected function initializeService($service, string $id, array $callDefinitions)
    {
        foreach ($callDefinitions as $callDefinition) {
            if (!is_array($callDefinition) || !isset($callDefinition['method'])) {
                throw new ContainerException("$id service calls must be arrays containing a 'method' key!");
            }
            elseif (!is_callable([$service, $callDefinition['method']])) {
                throw new ContainerException("$id service asks for call to uncallable method: {$callDefinition['method']}");
            }
            
            $arguments = isset($callDefinition['arguments']) ? $this->resolveArguments($callDefinition['arguments']) : [];

            call_user_func_array([$service, $callDefinition['method']], $arguments);
        }
    }
}