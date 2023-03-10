<?php

namespace App\Core\Database\Operation;

use App\Core\Contracts\Database\DatabaseOperation;
use App\Core\Exceptions\DatabaseUpdateException;

class UpdateOperation extends DatabaseOperation {
    public function __construct(
        protected string $table, 
        protected array $fields_values
    ) 
    {
        $this->handleOperationValue();
    }

    protected function handleOperationValue() 
    {
        $updateString = 'UPDATE ' . $this->table . ' SET ';

        if (empty($this->fields_values)) {
            throw new DatabaseUpdateException('There are no fields provided!');
        }

        $updates = [];

        foreach ($this->fields_values as $field => $value) {
            $updates[] = $field . '=' . (is_string($value) ? "'$value'" : $value);
        }

        $this->value = $updateString . implode(',', $updates);
    }
}