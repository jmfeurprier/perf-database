<?php

namespace perf\Db;

/**
 * This class acts as a registry for database connections, allowing to interact with multiple databases
 *   within the same application.
 *
 */
class ConnectionPool
{

    /**
     * Database connections.
     *
     * @var {string:Connection}
     */
    private $connections = array();

    /**
     * Stores a new database connection with provided credentials.
     *
     * @param string $connectionId
     * @param Credentials $credentials
     * @return void
     */
    public function prepare($connectionId, Credentials $credentials)
    {
        $connection = Connection::fromCredentials($credentials);

        $this->store($connectionId, $connection);
    }

    /**
     * Stores an existing database connection.
     *
     * @param string $connectionId
     * @param Connection $connection
     * @return void
     */
    public function store($connectionId, Connection $connection)
    {
        $connectionId = (string) $connectionId;

        $this->connections[$connectionId] = $connection;
    }

    /**
     * Returns the database connection with provided identifier, or throws an exception if not found.
     *
     * @param string $connectionId
     * @return Connection
     * @throws \DomainException
     */
    public function fetch($connectionId)
    {
        $connectionId = (string) $connectionId;

        if ($this->exist($connectionId)) {
            return $this->connections[$connectionId];
        }

        throw new \DomainException("Requested database connection '{$connectionId}' does not exist.");
    }

    /**
     * Returns true if a database connection with provided identifier is stored in the pool.
     *
     * @param string $connectionId
     * @return bool
     */
    public function exist($connectionId)
    {
        return array_key_exists((string) $connectionId, $this->connections);
    }
}
