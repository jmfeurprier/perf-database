<?php

namespace perf\Db;

/**
 *
 */
class QueryFilterTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testGetClause()
    {
        $clause = 'id IS NOT NULL';

        $queryFilter = new QueryFilter($clause);

        $this->assertSame($clause, $queryFilter->getClause());
    }

    /**
     *
     */
    public function testGetParametersWithoutParameters()
    {
        $clause = 'id IS NOT NULL';

        $queryFilter = new QueryFilter($clause);

        $resultParameters = $queryFilter->getParameters();

        $this->assertInternalType('array', $resultParameters);
        $this->assertCount(0, $resultParameters);
    }

    /**
     *
     */
    public function testGetParametersWithOneParameter()
    {
        $clause    = 'id = ?';
        $parameter = 123;

        $parameters = array(
            $parameter,
        );

        $queryFilter = new QueryFilter($clause, $parameters);

        $resultParameters = $queryFilter->getParameters();

        $this->assertInternalType('array', $resultParameters);
        $this->assertCount(1, $resultParameters);
        $this->assertContains($parameter, $resultParameters);
    }

    /**
     *
     */
    public function testGetParametersWithManyParameters()
    {
        $clause             = 'id = ? AND status = ?';
        $parameterPrimary   = 123;
        $parameterSecondary = 'foo';

        $parameters = array(
            $parameterPrimary,
            $parameterSecondary,
        );

        $queryFilter = new QueryFilter($clause, $parameters);

        $resultParameters = $queryFilter->getParameters();

        $this->assertInternalType('array', $resultParameters);
        $this->assertCount(2, $resultParameters);
        $this->assertContains($parameterPrimary, $resultParameters);
        $this->assertContains($parameterSecondary, $resultParameters);
    }

    /**
     *
     */
    public function testMergeAnd()
    {
        $clausePrimary      = 'id = ?';
        $parameterPrimary   = 123;
        $clauseSecondary    = 'status = ?';
        $parameterSecondary = 'foo';

        $parametersPrimary = array(
            $parameterPrimary,
        );

        $parametersSecondary = array(
            $parameterSecondary,
        );

        $expectedClause = "({$clausePrimary}) AND ({$clauseSecondary})";

        $queryFilterPrimary = new QueryFilter($clausePrimary, $parametersPrimary);
        $queryFilterSecondary = new QueryFilter($clauseSecondary, $parametersSecondary);

        $result = $queryFilterPrimary->mergeAnd($queryFilterSecondary);

        $this->assertSame($expectedClause, $result->getClause());

        $resultParameters = $result->getParameters();

        $this->assertInternalType('array', $resultParameters);
        $this->assertCount(2, $resultParameters);
        $this->assertContains($parameterPrimary, $resultParameters);
        $this->assertContains($parameterSecondary, $resultParameters);
    }
}
