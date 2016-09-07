<?php

namespace Charcoal\Admin\Search;

use \DateTime;
use \DateTimeInterface;

use \Charcoal\Admin\AdminWidget;

/**
 * Search widget to show the latest searched-for terms and their results.
 */
class TopSearchWidget extends AdminWidget
{
    /**
     * @var DateTimeInterface $startDate
     */
    private $startDate;

    /**
     * @var DateTimeInterface $endDate
     */
    private $endDate;

    /**
     * @param string|DateTimeInterface|null $date The starting date-time.
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
}
