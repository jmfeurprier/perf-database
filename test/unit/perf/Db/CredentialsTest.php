<?php

namespace perf\Db;

/**
 *
 */
class CredentialsTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testGetDriver()
    {
        $driver = 'foo';
        $host   = 'bar';

        $credentials = new Credentials($driver, $host);

        $result = $credentials->getDriver();

        $this->assertSame($driver, $result);
    }

    /**
     *
     */
    public function testGetHost()
    {
        $driver = 'foo';
        $host   = 'bar';

        $credentials = new Credentials($driver, $host);

        $result = $credentials->getHost();

        $this->assertSame($host, $result);
    }

    /**
     *
     */
    public function testGetUsernameDefault()
    {
        $driver = 'foo';
        $host   = 'bar';

        $credentials = new Credentials($driver, $host);

        $result = $credentials->getUsername();

        $this->assertNull($result);
    }

    /**
     *
     */
    public function testGetPasswordDefault()
    {
        $driver = 'foo';
        $host   = 'bar';

        $credentials = new Credentials($driver, $host);

        $result = $credentials->getPassword();

        $this->assertNull($result);
    }

    /**
     *
     */
    public function testGetDatabaseDefault()
    {
        $driver = 'foo';
        $host   = 'bar';

        $credentials = new Credentials($driver, $host);

        $result = $credentials->getDatabase();

        $this->assertNull($result);
    }

    /**
     *
     */
    public function testGetCharsetDefault()
    {
        $driver = 'foo';
        $host   = 'bar';

        $credentials = new Credentials($driver, $host);

        $result = $credentials->getCharset();

        $this->assertNull($result);
    }
}
