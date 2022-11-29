<?php

namespace App\Core\References;

/**
 * Reference class that holds the reference name during
 * dependency injection.
 */
abstract class AbstractReference 
{
    /**
     * The name of the reference
     *
     * @var string
     */
    private string $name;

    /**
     * Constructs a new reference class with the given
     * reference name.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns the name of the reference
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}