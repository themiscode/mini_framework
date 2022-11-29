<?php

namespace App\Core\Database\Operation;

use App\Core\Contracts\Database\DatabaseOperation;
use App\Core\Exceptions\DatabaseInsertException;

class InsertOperation extends DatabaseOperation {
    public function __construct(
        protected string $table, 
        protected array $fields_values
    )
    {
        $this->handleOperationValue();
    }
    protected function handleOperationValue() {
        $insertString = 'INSERT INTO ' . $this->table . ' ';

        if (empty($this->fields_values)) {
            throw new DatabaseInsertException('There are no fields provided!');
        }

        $insertString .= '(' . implode(',', array_keys($this->fields_values)) . ')';

        $sqlValues = array_values(
            array_map(
                fn ($value) => is_string($value) ? "'$value'" : $value, 
                $this->fields_values
            )
        );

        $values = implode(',', $sqlValues);

        $insertString .= ' VALUES (' . $values . ');';

        $this->value = $insertString;
    }
}