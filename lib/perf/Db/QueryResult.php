<?php

namespace perf\Db;

/**
 *
 *
 */
class QueryResult extends \IteratorIterator
{

    /**
     *
     *
     * @var \PDOStatement
     */
    private $statement;

    /**
     * Constructor.
     *
     * @param \PDOStatement $statement
     * @return void
     */
    public function __construct(\PDOStatement $statement)
    {
        parent::__construct($statement);

        $this->statement = $statement;
    }

    /**
     * Returns how many rows the last DELETE/INSERT/REPLACE/UPDATE query affected.
     *
     * @return int
     */
    public function getAffectedRows()
    {
        return $this->statement->rowCount();
    }
}
