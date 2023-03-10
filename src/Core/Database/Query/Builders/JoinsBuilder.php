<?php

namespace App\Core\Database\Query\Builders;

class JoinsBuilder 
{
    public function __construct(protected array $joins) {}

    public function unifyJoins() 
    {
        $length = count($this->joins);

        if (!$length) {
            return '';
        }

        $joinString = '';

        foreach ($this->joins as $index => $join) {
            $joinString .= $join;

            if (($index + 1) < $length) {
                $joinString .= ' ';
            }
        }

        return $joinString;
    }
}