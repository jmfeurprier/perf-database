<?php

namespace perf\Db;

/**
 * This class allows for SQL "WHERE" and "HAVING" clauses to be built safely and automatically.
 *
 */
class QueryFilter
{

    /**
     * Clause of the current filter, with question marks as parameter placeholders.
     *
     * @var string
     */
    private $clause;

    /**
     * List of parameters to be passed with the clause, which will replace question mark placeholders.
     *
     * @var array
     */
    private $parameters;

    /**
     * Returns a new builder.
     *
     * @return QueryFilterBuilder
     */
    public static function createBuilder()
    {
        return new QueryFilterBuilder();
    }

    /**
     * Static constructor.
     *
     * @param string $clause Clause for filtering results with question marks as parameter placeholders
     *                       (ex: "id = ? AND state = ?").
     * @param array $parameters List of parameters to be passed to the clause (ex: array(123, "online")).
     * @return QueryFilter A pre-defined instance which filters nothing.
     */
    public static function create($clause, array $parameters = array())
    {
        return new self($clause, $parameters);
    }

    /**
     * Provides a ready to use filter, which will do no filtering.
     *
     * @return QueryFilter A pre-defined instance which filters nothing.
     */
    public static function none()
    {
        return new self('1 = 1');
    }

    /**
     * Constructor.
     *
     * @param string $clause Clause for filtering results with question marks as parameter placeholders
     *                       (ex: "id = ? AND state = ?").
     * @param array $parameters List of parameters to be passed to the clause (ex: array(123, "online")).
     * @return void
     */
    public function __construct($clause, array $parameters = array())
    {
        $this->clause     = (string) $clause;
        $this->parameters = $parameters;
    }

    /**
     * Combines two filters together with a "AND" operator. Current and provided instances will not be altered;
     *   the returned new instance will hold the result of the computation.
     *
     * @param QueryFilter $filter The filter to be added to the current instance.
     * @return QueryFilter
     */
    public function mergeAnd(self $filter)
    {
        $clause     = "({$this->clause}) AND ({$filter->clause})";
        $parameters = array_merge($this->parameters, $filter->parameters);

        return new self($clause, $parameters);
    }

    /**
     * Combines two filters together with a "OR" operator. Current and provided instances will not be altered;
     *   the returned new instance will hold the result of the computation.
     *
     * @param QueryFilter $filter The filter to be added to the current instance.
     * @return QueryFilter
     */
    public function mergeOr(self $filter)
    {
        $clause     = "({$this->clause}) OR ({$filter->clause})";
        $parameters = array_merge($this->parameters, $filter->parameters);

        return new self($clause, $parameters);
    }

    /**
     *
     *
     * @return string
     */
    public function getClause()
    {
        return $this->clause;
    }

    /**
     *
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
