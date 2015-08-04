<?php

namespace perf\Db;

/**
 * This class allows for SQL "ORDER BY" clause to be built automatically.
 *
 */
class QuerySorting
{

    const ASC  = 'ASCENDING';
    const DESC = 'DESCENDING';

    /**
     *
     *
     * @var array
     */
    private $sortings = array();

    /**
     * Static constructor.
     *
     * @param array $sortings
     * @return QuerySorting
     */
    public static function create(array $sortings)
    {
        return new QuerySorting($sortings);
    }

    /**
     *
     *
     * @return QuerySorting
     */
    public static function none()
    {
        static $sortings = array();

        return new self($sortings);
    }

    /**
     *
     *
     * @return QuerySorting
     */
    public static function ascending($column)
    {
        $sortings = array($column => self::ASC);

        return new self($sortings);
    }

    /**
     *
     *
     * @return QuerySorting
     */
    public static function descending($column)
    {
        $sortings = array($column => self::DESC);

        return new self($sortings);
    }

    /**
     *
     *
     * @return Sorting
     */
    public static function random()
    {
        $sortings = array('RAND()' => self::ASC);

        return new self($sortings);
    }

    /**
     * Constructor.
     *
     * @param array $sortings
     * @return void
     * @throws \InvalidArgumentException
     */
    public function __construct(array $sortings)
    {
        foreach ($sortings as $column => $direction) {
            $this->addColumnSorting($column, $direction);
        }
    }

    /**
     *
     *
     * @param string $column
     * @param string $direction
     * @return void
     * @throws \InvalidArgumentException
     */
    private function addColumnSorting($column, $direction)
    {
        if (!is_string($column) || ('' === $column)) {
            throw new \InvalidArgumentException();
        }

        static $directions = array(
            self::ASC,
            self::DESC,
        );

        if (!in_array($direction, $directions, true)) {
            throw new \InvalidArgumentException();
        }

        $this->sortings[$column] = $direction;
    }

    /**
     *
     *
     * @param Sorting $sorting
     * @return void
     */
    public function add(self $sorting)
    {
        $this->sortings = array_merge($this->sortings, $sorting->sortings);
    }

    /**
     *
     *
     * @return string
     */
    public function getClause()
    {
        $sqlSortingsList = array();

        foreach ($this->sortings as $column => $direction) {
            $sqlDirection = (self::DESC === $direction) ? 'DESC' : 'ASC';

            $sqlSortingsList[] = "{$column} {$sqlDirection}";
        }

        if (count($sqlSortingsList) > 0) {
            return join(', ', $sqlSortingsList);
        }

        return 'NULL';
    }
}
