<?php

namespace Charcoal\Admin\Widget\Search;

use \DateTime;
use \DateTimeInterface;
use \InvalidArgumentException;

use \PDO;

use \Pimple\Container;

use \Charcoal\Admin\AdminWidget;

/**
 * A basic search history widget.
 */
abstract class AbstractSearchHistoryWidget extends AdminWidget
{
    /**
     * The default lower bound to filter searches by.
     *
     * @const string
     */
    const DEFAULT_FROM_DATE  = '30 days ago';

    /**
     * The default upper bound to filter searches by.
     *
     * @const string
     */
    const DEFAULT_UNTIL_DATE = 'now';

    /**
     * The lower bound (exclusive) for a search's timestamp to filter by.
     *
     * The default is to filter by 30 days ago (@see self::DEFAULT_FROM_DATE).
     *
     * @var DateTimeInterface
     */
    private $startDate;

    /**
     * The upper bound (inclusive) for a search's timestamp to filter by.
     *
     * The default is to filter by the current time (@see self::DEFAULT_UNTIL_DATE).
     *
     * @var DateTimeInterface
     */
    private $endDate;

    /**
     * The latest search requests.
     *
     * @var SearchLog[]
     */
    private $searchHistory;

    /**
     * Store the collection loader for the current class.
     *
     * @var CollectionLoader
     */
    private $collectionLoader;

    /**
     * Inject dependencies from a DI Container.
     *
     * @param  Container $container A dependencies container instance.
     * @return void
     */
    public function setDependencies(Container $container)
    {
        parent::setDependencies($container);

        $this->collectionLoader = $container['model/collection/loader'];
    }

    /**
     * Set the lower bound (exclusive) for a search's timestamp to filter by.
     *
     * @param string|DateTimeInterface|null $date The starting date/time.
     * @throws InvalidArgumentException If the date/time is invalid.
     * @return TopSearchWidget Chainable
     */
    public function setStartDate($date)
    {
        if ($date === null || $date === '') {
            $this->startDate = new DateTime(self::DEFAULT_FROM_DATE);
            return $this;
        }

        if (is_string($date)) {
            $date = new DateTime($date);
        }

        if (!$date instanceof DateTimeInterface) {
            throw new InvalidArgumentException(
                'Invalid "Start Date" value. Must be a date/time string or a DateTimeInterface object.'
            );
        }

        $this->startDate = $date;

        return $this;
    }

    /**
     * Retrieve the lower bound (exclusive) for a search's timestamp to filter by.
     *
     * @return DateTimeInterface
     */
    public function startDate()
    {
        if ($this->startDate === null) {
            $this->setStartDate(null);
        }

        return $this->startDate;
    }

    /**
     * Set the upper bound (inclusive) for a search's timestamp to filter by.
     *
     * @param string|DateTimeInterface|null $date The ending date/time.
     * @throws InvalidArgumentException If the date/time is invalid.
     * @return TopSearchWidget Chainable
     */
    public function setEndDate($date)
    {
        if ($date === null || $date === '') {
            $this->endDate = new DateTime(self::DEFAULT_UNTIL_DATE);
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
     * Retrieve the upper bound (inclusive) for a search's timestamp to filter by.
     *
     * @return DateTimeInterface
     */
    public function endDate()
    {
        if ($this->endDate === null) {
            $this->setEndDate(null);
        }

        return $this->endDate;
    }

    /**
     * Retrieve the search history.
     *
     * @return SearchLog[]
     */
    public function searchHistory()
    {
        if ($this->searchHistory === null) {
            $this->searchHistory = $this->loadSearchHistory();
        }

        return $this->searchHistory;
    }

    /**
     * Determine if there's a search history.
     *
     * @return boolean
     */
    public function hasSearchHistory()
    {
        return (count($this->searchHistory()) > 0);
    }

    /**
     * Load the search history from the source.
     *
     * @return SearchLog[]
     */
    abstract public function loadSearchHistory();
}
