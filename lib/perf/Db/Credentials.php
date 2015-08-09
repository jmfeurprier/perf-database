<?php

namespace perf\Db;

/**
 * This class allows to encapsulate database connection credentials.
 *
 */
class Credentials
{

    /**
     * Name of the PDO driver to be used ("mysql", "sqlite", etc).
     *
     * @var string
     */
    private $driver;

    /**
     * Host where the DBMS can be found.
     *
     * @var string
     */
    private $host;

    /**
     * Username to provide to the DBMS (optional).
     *
     * @var null|string
     */
    private $username;

    /**
     * Password to provide to the DBMS (optional).
     *
     * @var null|string
     */
    private $password;

    /**
     * Database to be selected (optional).
     *
     * @var null|string
     */
    private $database;

    /**
     * Charset to use for the connection (optional).
     *
     * @var null|string
     */
    private $charset;

    /**
     * Constructor.
     *
     * @param string $driver Name of the PDO driver to be used ("mysql", "sqlite", etc).
     * @param string $host Host where the DBMS can be found.
     * @param null|string $username Username to provide to the DBMS (optional).
     * @param null|string $password Password to provide to the DBMS (optional).
     * @param null|string $database Database to be selected (optional).
     * @param null|string $charset Charset to use for the connection (optional).
     * @return void
     */
    public function __construct($driver, $host, $username = null, $password = null, $database = null, $charset = null)
    {
        $this->driver = (string) $driver;
        $this->host   = (string) $host;

        if (null !== $username) {
            $this->username = (string) $username;
        }

        if (null !== $password) {
            $this->password = (string) $password;
        }

        if (null !== $database) {
            $this->database = (string) $database;
        }

        if (null !== $charset) {
            $this->charset = (string) $charset;
        }
    }

    /**
     *
     *
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     *
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     *
     *
     * @return null|string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     *
     *
     * @return null|string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     *
     *
     * @return null|string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     *
     *
     * @return null|string
     */
    public function getCharset()
    {
        return $this->charset;
    }
}
