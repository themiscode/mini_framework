<?php

namespace App\Core\Contracts\Database;

abstract class DatabaseStatement 
{
    protected string $value;
    
    public function __construct(string $value) 
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
    
    public function __toString() 
    {
        return (string) $this->getValue();
    }
}