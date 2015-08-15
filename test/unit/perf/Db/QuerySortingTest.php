<?php

namespace perf\Db;

/**
 *
 */
class QuerySortingTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testGetClauseWithoutSorting()
    {
        $sortings = array();

        $querySorting = new QuerySorting($sortings);

        $result = $querySorting->getClause();

        $this->assertSame('NULL', $result);
    }

    /**
     *
     */
    public function testGetClauseWithOneSorting()
    {
        $sortings = array(
            'foo' => QuerySorting::DESC,
        );

        $querySorting = new QuerySorting($sortings);

        $result = $querySorting->getClause();

        $this->assertSame('foo DESC', $result);
    }

    /**
     *
     */
    public function testGetClauseWithManySortings()
    {
        $sortings = array(
            'foo' => QuerySorting::DESC,
            'bar' => QuerySorting::ASC,
            'baz' => QuerySorting::DESC,
        );

        $querySorting = new QuerySorting($sortings);

        $result = $querySorting->getClause();

        $this->assertSame('foo DESC, bar ASC, baz DESC', $result);
    }

    /**
     *
     */
    public function testGetClauseWithNoneConstructor()
    {
        $querySorting = QuerySorting::none();

        $result = $querySorting->getClause();

        $this->assertSame('NULL', $result);
    }

    /**
     *
     */
    public function testGetClauseWithAscendingConstructor()
    {
        $querySorting = QuerySorting::ascending('foo');

        $result = $querySorting->getClause();

        $this->assertSame('foo ASC', $result);
    }

    /**
     *
     */
    public function testGetClauseWithDescendingConstructor()
    {
        $querySorting = QuerySorting::descending('foo');

        $result = $querySorting->getClause();

        $this->assertSame('foo DESC', $result);
    }

    /**
     *
     */
    public function testGetClauseWithRandomConstructor()
    {
        $querySorting = QuerySorting::random();

        $result = $querySorting->getClause();

        $this->assertSame('RAND() ASC', $result);
    }
}
