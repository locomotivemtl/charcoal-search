<?php

namespace Charcoal\Admin\Widget\Search;

use \DateTime;
use \DateTimeInterface;
use \InvalidArgumentException;
use \PDO;

use \Pimple\Container;

use \Charcoal\Admin\AdminWidget;

use \Charcoal\Search\SearchLog;

/**
 * Search widget to show the latest searched-for terms and their results.
 */
class NoResultsSearchWidget extends AdminWidget
{
    /**
     * @var DateTimeInterface $startDate
     */
    private $startDate;

    /**
     * @var DateTimeInterface $endDate
     */
    private $endDate;

    private $collectionLoader;
    private $noResultsSearches;

    public function setDependencies(Container $container)
    {
        parent::setDependencies($container);

        $this->collectionLoader = $container['model/collection/loader'];
    }

    /**
     * @param string|DateTimeInterface|null $date The starting date-time.
     * @throws InvalidArgumentException If the date format is not valid.
     * @return TopSearchWidget Chainable
     */
    public function setStartDate($date)
    {
        if ($date === null) {
            $this->startDate = new DateTime('30 days ago');
            return $this;
        }
        if (is_string($date)) {
            $date = new DateTime($date);
        }
        if (!($date instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "Start Date" value. Must be a date/time string or a DateTimeInterface object.'
            );
        }
        $this->startDate = $date;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function startDate()
    {
        if ($this->startDate === null) {
            return new DateTime('30 days ago');
        }
        return $this->startDate;
    }

    /**
     * @param string|DateTimeInterface|null $date The ending date-time.
     * @throws InvalidArgumentException If the date format is not valid.
     * @return TopSearchWidget Chainable
     */
    public function setEndDate($date)
    {
        if ($date === null) {
            $this->endDate = new DateTime('now');
            return $this;
        }
        if (is_string($date)) {
            $date = new DateTime($date);
        }
        if (!($date instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "End Date" value. Must be a date/time string or a DateTimeInterface object.'
            );
        }
        $this->endDate = $date;
        return $this;
    }


    /**
     * @return DateTimeInterface
     */
    public function endDate()
    {
        if ($this->endDate === null) {
            return new DateTime('now');
        }
        return $this->endDate;
    }

    public function noResultsSearches()
    {
        if ($this->noResultsSearches === null) {
            $this->noResultsSearches = $this->loadNoResultsSearches();
        }

        return $this->noResultsSearches;
    }

    public function hasNoResultsSearches()
    {
        $noResultsSearches = $this->noResultsSearches();
        return (count($noResultsSearches) > 0);
    }

    public function loadNoResultsSearches()
    {
        $proto = $this->modelFactory()->create(SearchLog::class);
        $source = $proto->source();
        $table = $source->table();

        $q = '
        SELECT
            `keyword`,
            COUNT(`keyword`) as num_searches,
            SUM(`num_results`) as num_results
        FROM
            `'.$table.'`
        WHERE
            `num_results` = 0
        AND
            `ts` > :start
        AND
            `ts` <= :end
        GROUP BY
            `keyword`
        ORDER BY
            num_searches DESC
        LIMIT
            20
        ';

        $sth = $source->dbQuery($q, [
            'start' =>$this->startDate()->format('Y-m-d H:i:s'),
            'end' => $this->endDate()->format('Y-m-d H:i:s')
        ]);

        $results = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
}
