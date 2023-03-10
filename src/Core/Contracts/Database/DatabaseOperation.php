<?php

namespace App\Core\Contracts\Database;

abstract class DatabaseOperation
{
    protected string $value;

    abstract protected function handleOperationValue();

    public function getValue() 
    {
        return $this->value;
    }
    
    public function __toString() 
    {
        return (string) $this->getValue();
    }
}