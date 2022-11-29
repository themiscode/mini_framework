<?php

namespace App\Core\Database;

use App\Core\Contracts\Database\DatabaseOperation;
use App\Core\Services\DatabaseService;

class SQLExpression 
{
    public function __construct(
        protected string $operationCode,
        protected DatabaseOperation $operation,
        protected string $joinStatement,
        protected string $whereStatement,
    ) {}

    public function __toString()
    {
        if (
            $this->operationCode === DatabaseService::UPDATE
            || $this->operationCode === DatabaseService::DELETE
        ) 
        {
            return $this->operation 
                . ($this->whereStatement ? ' ' . $this->whereStatement : '')
                . ';';
        }
        elseif ($this->operationCode === DatabaseService::SELECT) {
            return $this->operation 
                . ($this->joinStatement ? ' ' . $this->joinStatement : '')
                . ($this->whereStatement ? ' ' . $this->whereStatement : '')
                . ';';
        }

        return $this->operation . ';';
    }
}