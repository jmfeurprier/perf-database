<?php

namespace perf\Db;

/**
 *
 *
 */
class PreparedStatement
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
        $this->statement = $statement;
    }

    /**
     *
     *
     * @param array $parameters
     * @return QueryResult
     * @throws \RuntimeException
     */
    public function execute(array $parameters = array())
    {
        $this->statement->execute($parameters);

        return new QueryResult($this->statement);
    }
}
