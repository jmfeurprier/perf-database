<?php

namespace perf\Db;

/**
 * This class allows to interact with a database, wrapping a PDO object.
 *
 * @see http://www.php.net/pdo
 */
class Connection
{

    /**
     * Connection credentials.
     *
     * @var Credentials
     */
    private $credentials;

    /**
     * Wrapped PDO connection.
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * Static constructor.
     *
     * @param Credentials $credentials
     * @return void
     */
    public static function fromCredentials(Credentials $credentials)
    {
        $connection = new self();
        $connection->credentials = $credentials;

        return $connection;
    }

    /**
     * Static constructor.
     *
     * @param \PDO $pdo
     * @return void
     */
    public static function fromPdo(\PDO $pdo)
    {
        $connection = new self();
        $connection->pdo = $pdo;

        return $connection;
    }

    /**
     * Constructor.
     * Private, use static constructors instead.
     *
     * @return void
     */
    private function __construct()
    {
    }

    /**
     * Sets the charset to be used for content being retrieved from or sent to the DBMS.
     *
     * @param string $charset
     * @return void
     * @throws \RuntimeException
     */
    public function setCharset($charset)
    {
        $this->getPdo()->exec("SET NAMES '{$charset}'");
    }

    /**
     * Sets the database to be used.
     *
     * @param string $database
     * @return void
     * @throws \RuntimeException
     */
    public function selectDatabase($database)
    {
        $sqlDatabase = $this->escapeAndQuoteDatabase($database);

        $this->getPdo()->exec("USE {$sqlDatabase}");
    }

    /**
     * Executes a SQL query which does not gather results (ie, statements like DELETE, INSERT, UPDATE, etc).
     *
     * @param string $sql SQL query to be executed.
     * @param mixed[] $parameters Optional parameters
     * @return int Number of rows affected by provided query.
     * @throws \RuntimeException
     */
    public function execute($sql, array $parameters = array())
    {
        if (count($parameters) > 0) {
            $pdoStatement = $this->pdoPrepare($sql);
            $pdoStatement->execute($parameters);
            $affectedRows = $pdoStatement->rowCount();
        } else {
            $affectedRows = $this->getPdo()->exec($sql);
        }

        return $affectedRows;
    }

    /**
     * Executes a SQL query which gathers results (ie, statements like SELECT, SHOW, etc).
     *
     * @param string $sql SQL query to be executed.
     * @param null|mixed[] $parameters Optional parameters
     * @return QueryResult Wrapper for the result of the provided query.
     * @throws \RuntimeException
     */
    public function query($sql, array $parameters = null)
    {
        $pdoStatement = $this->pdoPrepare($sql);
        $pdoStatement->execute($parameters);

        return new QueryResult($pdoStatement);
    }

    /**
     * Prepares a SQL statement.
     *
     * @param string $sql
     * @return PreparedStatement
     * @throws \RuntimeException
     */
    public function prepare($sql)
    {
        $pdoStatement = $this->pdoPrepare($sql);

        return new PreparedStatement($pdoStatement);
    }

    /**
     * Executes a SQL query which gathers a single result (ie, statements like SELECT COUNT(*), etc).
     *
     * @param string $sql SQL query to be executed.
     * @param null|mixed[] $parameters Optional parameters
     * @return mixed Result of the provided query.
     * @throws \RuntimeException
     */
    public function queryValue($sql, array $parameters = null)
    {
        $pdoStatement = $this->pdoPrepare($sql);
        $pdoStatement->execute($parameters);

        return $pdoStatement->fetchColumn(0);
    }

    /**
     * Prepares a PDO statement.
     *
     * @param string $sql
     * @return \PDOStatement
     * @throws \RuntimeException
     */
    private function pdoPrepare($sql)
    {
        try {
            $pdoStatement = $this->getPdo()->prepare($sql);
        } catch (\PDOException $e) {
            throw new \RuntimeException("Failed to prepare SQL query: {$sql} << {$e->getMessage()}", 0, $e);
        }

        return $pdoStatement;
    }

    /**
     * Begins a database transaction.
     *
     * @return void
     * @throws \RuntimeException
     */
    public function beginTransaction()
    {
        if (!$this->getPdo()->beginTransaction()) {
            throw new \RuntimeException('Failed to begin transaction.');
        }
    }

    /**
     * Commits a database transaction.
     *
     * @return void
     * @throws \RuntimeException
     */
    public function commitTransaction()
    {
        if (!$this->getPdo()->commit()) {
            throw new \RuntimeException('Failed to commit transaction.');
        }
    }

    /**
     * Rolls back a database transaction.
     *
     * @return void
     * @throws \RuntimeException
     */
    public function rollBackTransaction()
    {
        if (!$this->getPdo()->rollBack()) {
            throw new \RuntimeException('Failed to roll-back transaction.');
        }
    }

    /**
     * Escapes and quotes provided scalar value.
     *
     * @param string $value Value to be escaped and quoted.
     * @return string The escaped and quoted value.
     * @throws \RuntimeException
     */
    public function escapeAndQuote($value)
    {
        if (null === $value) {
            $pdoType = \PDO::PARAM_NULL;
        } else {
            $pdoType = \PDO::PARAM_STR;
        }

        return $this->getPdo()->quote($value, $pdoType);
    }

    /**
     * Escapes and quotes provided database name.
     *
     * @param string $database Database name to be escaped and quoted.
     * @return string The escaped and quoted database name.
     */
    public function escapeAndQuoteDatabase($database)
    {
        return "`{$database}`";
    }

    /**
     * Escapes and quotes provided table name.
     *
     * @param string $table Table name to be escaped and quoted.
     * @return string The escaped and quoted table name.
     */
    public function escapeAndQuoteTable($table)
    {
        return "`{$table}`";
    }

    /**
     * Escapes and quotes provided table field name.
     *
     * @param string $field Table field name to be escaped and quoted.
     * @return string The escaped and quoted table field name.
     */
    public function escapeAndQuoteField($field)
    {
        return "`{$field}`";
    }

    /**
     * Returns the last auto-incremental value after a INSERT query.
     *
     * @return int
     * @throws \RuntimeException
     */
    public function getInsertId()
    {
        return (int) $this->getPdo()->lastInsertId();
    }

    /**
     * Returns the wrapped PDO object.
     *
     * @return \PDO
     * @throws \RuntimeException
     */
    public function getPdo()
    {
        if (!$this->pdo) {
            $this->connect();
        }

        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        return $this->pdo;
    }

    /**
     * Attempts to connect to database.
     *
     * @return void
     * @throws \RuntimeException
     */
    private function connect()
    {
        $dsn = "{$this->credentials->getDriver()}:host={$this->credentials->getHost()}";

        try {
            $pdo = new \PDO($dsn, $this->credentials->getUsername(), $this->credentials->getPassword());
        } catch (\PDOException $e) {
            throw new \RuntimeException('Failed to connect to database.', 0, $e);
        }

        $this->pdo = $pdo;

        if (null !== $this->credentials->getCharset()) {
            $this->setCharset($this->credentials->getCharset());
        }

        if (null !== $this->credentials->getDatabase()) {
            $this->selectDatabase($this->credentials->getDatabase());
        }
    }
}
