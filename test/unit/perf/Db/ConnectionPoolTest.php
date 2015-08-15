<?php

namespace perf\Db;

/**
 *
 */
class ConnectionPoolTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
        $this->connectionPool = new ConnectionPool($sortings);
    }

    /**
     *
     * @expectedException \DomainException
     */
    public function testFetchWithNotExistingConnection()
    {
        $this->connectionPool->fetch('foo');
    }

    /**
     *
     */
    public function testFetchWithExistingConnection()
    {
        $connection = $this->getMockBuilder('\\' . __NAMESPACE__ . '\\Connection')->disableoriginalConstructor()->getMock();

        $this->connectionPool->store('foo', $connection);

        $result = $this->connectionPool->fetch('foo');

        $this->assertSame($connection, $result);
    }

    /**
     *
     */
    public function testExistWithNotExistingConnection()
    {
        $result = $this->connectionPool->exist('foo');

        $this->assertFalse($result);
    }

    /**
     *
     */
    public function testExistWithExistingConnection()
    {
        $connection = $this->getMockBuilder('\\' . __NAMESPACE__ . '\\Connection')->disableoriginalConstructor()->getMock();

        $this->connectionPool->store('foo', $connection);

        $result = $this->connectionPool->exist('foo');

        $this->assertTrue($result);
    }
}
