<?php

namespace Charcoal\Search;

use \DateTime;
use \DateTimeInterface;
use \InvalidArgumentException;

// Module `charcoal-core` dependencies
use \Charcoal\Model\AbstractModel;

/**
 * Search logs should be saved every time a client initiates a search request.
 */
class SearchLog extends AbstractModel
{
    /**
     * The search identifier this specific search log belongs to.
     * @var string $searchIdent
     */
    private $searchIdent;

    /**
     * The searched keyword.
     * @var string $keyword
     */
    private $keyword;

    /**
     * Number of search results.
     * @var array $numResults
     */
    private $numResults;

    /**
     * Detailed results, if available.
     * @var array $results
     */
    private $results;

    /**
     * Timestamp of the search (date-time when the search was performed).
     * @var DateTimeInterface
     */
    private $ts;

    /**
     * Client IP.
     * @var string $ip
     */
    private $ip;

    /**
     * Client session ID, if any.
     * @var string $sessionId
     */
    private $sessionId;

    /**
     * The language code.
     * @var string $lang
     */
    private $lang;

    /**
     * @param string $ident The search identifier.
     * @throws InvalidArgumentException If the search ident is not a string.
     * @return SearchLog Chainable
     */
    public function setSearchIdent($ident)
    {
        if (!is_string($ident)) {
            throw new InvalidArgumentException(
                'Search ident must be a string.'
            );
        }
        $this->searchIdent = $ident;
        return $this;
    }

    /**
     * @return string
     */
    public function searchIdent()
    {
        return $this->searchIdent;
    }

    /**
     * @param string $kw The searched term / keyword.
     * @throws InvalidArgumentException If the keyword is not a string.
     * @return SearchLog Chainable
     */
    public function setKeyword($kw)
    {
        if (!is_string($kw)) {
            throw new InvalidArgumentException(
                'Keyword '
            );
        }
        $this->keyword = $kw;
        return $this;
    }

    /**
     * @return string
     */
    public function keyword()
    {
        return $this->keyword;
    }

    /**
     * @param integer $num The number of results from search.
     * @return SearchLog Chainable
     */
    public function setNumResults($num)
    {
        $this->numResults = (int)$num;
        return $this;
    }

    /**
     * @return integer
     */
    public function numResults()
    {
        return $this->numResults;
    }

    /**
     * @param mixed $results The search results data, if available.
     * @throws InvalidArgumentException If the results is not an array or invalid JSON.
     * @return SearchLog Chainable
     */
    public function setResults($results)
    {
        if ($results === null) {
            $this->results = null;
        } elseif (is_string($results)) {
            $this->results = json_decode($results, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidArgumentException(
                    sprintf('Invalid JSON for search results: "%s"', $results)
                );
            }
        } elseif (is_array($results)) {
            $this->results = $results;
        } else {
            throw new InvalidArgumentException(
                'Invalid search results type. Must be a JSON string, an array or null.'
            );
        }
        return $this;
    }

    /**
     * @return array
     */
    public function results()
    {
        return $this->results;
    }

    /**
     * @param DateTimeInterface|string|null $ts The timestamp (date-time the search occured).
     * @throws InvalidArgumentException If ts is not a valid date-time.
     * @return SearchLog Chainable
     */
    public function setTs($ts)
    {
        if ($ts === null) {
            $this->ts = null;
            return $this;
        }
        if (is_string($ts)) {
            $ts = new DateTime($ts);
        }
        if (!($ts instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "ts" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->ts = $ts;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function ts()
    {
        return $this->ts;
    }

    /**
     * @param string $ip The IP address of the client that searched.
     * @return SearchLog Chainable
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string
     */
    public function ip()
    {
        return $this->ip;
    }

    /**
     * @param string $lang The language code.
     * @return SearchLog Chainable
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * @return string
     */
    public function lang()
    {
        return $this->lang;
    }

    public function preSave()
    {
        parent::preSave();

        $this->setIp(getenv('REMOTE_ADDR') ? getenv('REMOTE_ADDR') : '');
        $this->setTs('now');

        if (!isset($this->lang)) {
            $this->setLang('');
        }

        return true;
    }
}
