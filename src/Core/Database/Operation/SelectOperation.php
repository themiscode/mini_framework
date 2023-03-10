<?php

namespace App\Core\Database\Operation;

use App\Core\Contracts\Database\DatabaseOperation;

class SelectOperation extends DatabaseOperation {
    public function __construct(
        protected string $table, 
        protected array $selects
    ) 
    {
        $this->handleOperationValue();
    }

    protected function handleOperationValue() 
    {
        $this->value = 'SELECT ' 
            . (!empty($this->selects) ? implode(',', $this->selects) : '*') 
            . ' FROM '
            . $this->table;
    }
}