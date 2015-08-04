<?php

namespace perf\Db;

/**
 *
 *
 */
class QueryFilterBuilder
{

    /**
     *
     * Temporary property.
     *
     * @var QueryFilter
     */
    private $queryFilter;

    /**
     *
     *
     * @return QueryFilterBuilder Fluent return.
     */
    public function equals($column, $value)
    {
        $clause      = "{$column} = ?";
        $parameters  = array($value);
        $queryFilter = new QueryFilter($clause, $parameters);

        $this->addFilter($queryFilter);

        return $this;
    }

    /**
     *
     *
     * @return QueryFilterBuilder Fluent return.
     */
    public function notEquals($column, $value)
    {
        $clause      = "{$column} <> ?";
        $parameters  = array($value);
        $queryFilter = new QueryFilter($clause, $parameters);

        $this->addFilter($queryFilter);

        return $this;
    }

    /**
     *
     *
     * @return QueryFilterBuilder Fluent return.
     */
    public function isNull($column)
    {
        $clause      = "{$column} IS NULL";
        $parameters  = array();
        $queryFilter = new QueryFilter($clause, $parameters);

        $this->addFilter($queryFilter);

        return $this;
    }

    /**
     *
     *
     * @return QueryFilterBuilder Fluent return.
     */
    public function isNotNull($column)
    {
        $clause      = "{$column} IS NOT NULL";
        $parameters  = array();
        $queryFilter = new QueryFilter($clause, $parameters);

        $this->addFilter($queryFilter);

        return $this;
    }

    /**
     *
     *
     * @return QueryFilterBuilder Fluent return.
     */
    public function in($column, $values)
    {
        $valueCount = count($values);

        if ($valueCount < 1) {
            throw new \InvalidArgumentException('No value provided.');
        }

        $parameterTokens = join(', ', array_fill(0, $valueCount, '?'));
        $clause          = "{$column} IN ({$parameterTokens})";
        $parameters      = $values;
        $queryFilter     = new QueryFilter($clause, $parameters);

        $this->addFilter($queryFilter);

        return $this;
    }

    /**
     *
     *
     * @return QueryFilterBuilder Fluent return.
     */
    public function notIn($column, $values)
    {
        $valueCount = count($values);

        if ($valueCount < 1) {
            throw new \InvalidArgumentException('No value provided.');
        }

        $parameterTokens = join(', ', array_fill(0, $valueCount, '?'));
        $clause          = "{$column} NOT IN ({$parameterTokens})";
        $parameters      = $values;
        $queryFilter     = new QueryFilter($clause, $parameters);

        $this->addFilter($queryFilter);

        return $this;
    }

    /**
     *
     *
     * @param QueryFilter $queryFilter
     * @return void
     */
    private function addFilter(QueryFilter $queryFilter)
    {
        if ($this->queryFilter) {
            $this->queryFilter = $this->queryFilter->mergeAnd($queryFilter);
        } else {
            $this->queryFilter = $queryFilter;
        }
    }

    /**
     *
     *
     * @return QueryFilter
     */
    public function build()
    {
        if ($this->queryFilter) {
            return $this->queryFilter;
        }

        throw new \RuntimeException('No query filter to build.');
    }
}
