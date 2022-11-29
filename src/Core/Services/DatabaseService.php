<?php

namespace App\Core\Services;

use App\Core\Database\Operation\DeleteOperation;
use App\Core\Database\Operation\InsertOperation;
use App\Core\Database\Operation\SelectOperation;
use App\Core\Database\Operation\UpdateOperation;
use App\Core\Database\Query\OrWhereInStatement;
use App\Core\Database\Query\OrWhereStatement;
use App\Core\Database\Query\WhereInStatement;
use App\Core\Database\Query\WhereStatement;
use App\Core\Database\Query\Builders\JoinsBuilder;
use App\Core\Database\Query\Builders\WheresBuilder;
use App\Core\Database\Query\JoinStatement;
use App\Core\Database\SQLExpression;
use App\Core\Exceptions\DatabaseConnectionException;

class DatabaseService
{
    public const INSERT = 'INSERT';
    public const UPDATE = 'UPDATE';
    public const DELETE = 'DELETE';
    public const SELECT = 'SELECT';

    protected \mysqli $dbConnection;
    protected array $wheres = [];
    protected array $orWheres = [];
    protected array $whereIns = [];
    protected array $orWhereIns = [];
    protected array $joins = [];
    protected array $selects = [];

    protected string $table = '';
    protected string $operationCode = '';

    protected array $fields_values = [];

    public function __construct(
        protected string $dbHost,
        protected string $dbUser,
        protected string $dbPass,
        protected string $dbName,
        protected int $dbPort,
    )
    {}

    public function where(string $column, string $operator, mixed $value) {
        $this->wheres[] = new WhereStatement(
                $column . $operator . (is_string($value) ? "'$value'" : $value)
            );

        return $this;
    }

    public function orWhere(string $column, string $operator, mixed $value) {
        $this->orWheres[] = new OrWhereStatement(
                $column . $operator . (is_string($value) ? "'$value'" : $value)
            );

        return $this;
    }

    public function whereIn(string $column, array $values) {
        $this->whereIns[] = new WhereInStatement(
                $column . ' IN ' . '(' . implode(',', $values) . ')'
            );

        return $this;
    }

    public function orWhereIn(string $column, array $values) {
        $this->orWhereIns[] = new OrWhereInStatement(
                $column . ' IN ' . '(' . implode(',', $values) . ')'
            );

        return $this;
    }

    public function join(string $table, string $alias = null, string $onClause = null) {
        $join = "JOIN $table";

        $join = $this->addAliasAndOnClauseToJoin($join, $alias, $onClause);

        $this->joins[] = new JoinStatement($join);

        return $this;
    }

    public function leftJoin(string $table, string $alias, string $onClause = null) {
        $join = "LEFT JOIN $table";

        $join = $this->addAliasAndOnClauseToJoin($join, $alias, $onClause);

        $this->joins[] = new JoinStatement($join);

        return $this;
    }

    public function rightJoin(string $table, string $alias, string $onClause = null) {
        $join = "RIGHT JOIN $table";

        $join = $this->addAliasAndOnClauseToJoin($join, $alias, $onClause);

        $this->joins[] = new JoinStatement($join);

        return $this;
    }

    protected function addAliasAndOnClauseToJoin(string $join, string $alias, string $onClause)
    {
        if ($alias) {
            $join .= " AS $alias";
        }

        if ($onClause) {
            $join .= " ON $onClause";
        }

        return $join;
    }

    public function table(string $table) {
        $this->table = $table;

        return $this;
    }

    public function select(string ...$columns)
    {
        $this->operationCode = static::SELECT;
        $this->selects = $columns;

        return $this;
    }

    public function insert(array $fields_values) {
        $this->operationCode = static::INSERT;
        $this->fields_values = $fields_values;

        return $this;
    }

    public function update(array $fields_values)
    {
        $this->operationCode = static::UPDATE;
        $this->fields_values = $fields_values;

        return $this;
    }

    public function delete()
    {
        $this->operationCode = static::DELETE;

        return $this;
    }

    public function rawQuery(string $sqlQuery)
    {
        $result = $this->connect()->dbConnection->query($sqlQuery);

        $this->reset();

        return $result;
    }

    public function execute() {
        $query = $this->prepareQuery();

        $result = $this->connect()->dbConnection->query($query);

        $this->reset();

        if (is_bool($result)) {
            return $result;
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    protected function prepareQuery() {
        $operation = null;
        
        if ($this->operationCode === static::UPDATE) {
            $operation = new UpdateOperation($this->table, $this->fields_values);
        }
        elseif ($this->operationCode === static::INSERT) {
            $operation = new InsertOperation($this->table, $this->fields_values);
        }
        elseif ($this->operationCode === static::SELECT) {
            $operation = new SelectOperation($this->table, $this->selects);
        }
        else {
            $operation = new DeleteOperation($this->table);
        }

        $expression = new SQLExpression(
            $this->operationCode,
            $operation,
            $this->compileJoins(),
            $this->compileWheres()
        );

        return $expression;
    }

    protected function compileWheres() {
        return (
            new WheresBuilder(
                $this->wheres, 
                $this->orWheres, 
                $this->whereIns, 
                $this->orWhereIns
            )
        )
        ->unifyWheres();
    }

    protected function compileJoins() {
        return (new JoinsBuilder($this->joins))->unifyJoins();
    }

    protected function connect() {
        $connection = new \mysqli(
            $this->dbHost,
            $this->dbUser,
            $this->dbPass,
            $this->dbName,
            $this->dbPort
        );
        if ($connection->connect_error) {
            throw new DatabaseConnectionException(
                "Database connection error with code: {$connection->connect_errno} and message {$connection->connect_error}"
            );
        }

        $this->dbConnection = $connection;

        return $this;
    }

    protected function reset() {
        $this->dbConnection->close();

        $this->wheres = [];
        $this->orWheres = [];
        $this->whereIns = [];
        $this->orWhereIns = [];
        $this->joins = [];
        $this->selects = [];
        $this->fields_values = [];
        $this->table = '';
        $this->operationCode = '';
    }
}
