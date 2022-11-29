<?php

namespace App\Core\Database\Query\Builders;

use App\Core\Database\Query\OrWhereInStatement;
use App\Core\Database\Query\OrWhereStatement;
use App\Core\Database\Query\WhereInStatement;
use App\Core\Database\Query\WhereStatement;

class WheresBuilder 
{
    public function __construct(
        private array $wheres,
        private array $orWheres,
        private array $whereIns,
        private array $orWhereIns,
    ) {}

    public function unifyWheres()
    {
        return $this->stringifyWheresArray(
            array_merge(
                $this->wheres,
                $this->whereIns,
                $this->orWheres,
                $this->orWhereIns
            )
        );
    }

    protected function stringifyWheresArray(array $wheresArray) {
        $length = count($wheresArray);

        if (!$length) {
            return '';
        }

        $whereString = '';

        foreach ($wheresArray as $index => $where) {
            if ($index > 0) {
                switch (get_class($where)) {
                    case WhereStatement::class || WhereInStatement::class:
                        $whereString .= ' AND ';
                        break;
                    case OrWhereStatement::class || OrWhereInStatement::class:
                        $whereString .= ' OR ';
                        break;
                    default:
                        break;
                }
            }

            $whereString .= $where;
        }

        return 'WHERE ' . $whereString;
    }
}