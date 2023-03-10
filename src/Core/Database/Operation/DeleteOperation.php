<?php

namespace App\Core\Database\Operation;

use App\Core\Contracts\Database\DatabaseOperation;

class DeleteOperation extends DatabaseOperation {
    public function __construct(protected string $table)
    {
        $this->handleOperationValue();
    }

    protected function handleOperationValue() 
    {
        $this->value = 'DELETE FROM ' . $this->table;
    }
}