<?php

namespace Charcoal\Search;

use \DateTime;
use \DateTimeInterface;
use \Traversable;
use \InvalidArgumentException;

// From 'charcoal-core'
use \Charcoal\Model\AbstractModel;

/**
 * Search logs should be saved every time a client initiates a search request.
 */
class SearchLog extends AbstractModel implements SearchLogInterface
{
    /**
     * The search identifier this specific search log belongs to.
     *
     * @var string
     */
    private $searchIdent;

    /**
     * The searched keyword.
     *
     * @var string
     */
    private $keyword;

    /**
     * The search options, if defined.
     *
     * @var array|null
     */
    private $options;

    /**
     * Number of search results.
     *
     * @var integer|null
     */
    private $numResults;

    /**
     * Detailed results, if available.
     *
     * @var array|null
     */
    private $results;

    /**
     * Client session ID
     *
     * @var string|null
     */
    private $sessionId;

    /**
     * Client IP address of the end-user.
     *
     * @var integer|null
     */
    private $ip;

    /**
     * Language of the end-user or source URI.
     *
     * @var string|null
     */
    private $lang;

    /**
     * The search origin; an identifier representing where the search was executed from.
     *
     * @var string|null
     */
    private $origin;

    /**
     * Timestamp of the search request.
     *
     * @var DateTimeInterface|null
     */
    private $ts;

    /**
     * Set the log's associated search identifier.
     *
     * @param  string $ident The search identifier.
     * @throws InvalidArgumentException If the identifier is not a string.
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
     * Retrieve the log's associated search identifier.
     *
     * @return string
     */
    public function searchIdent()
    {
        return $this->searchIdent;
    }

    /**
     * Set the searched term.
     *
     * @param  string $kw The searched term / keyword.
     * @throws InvalidArgumentException If the keyword is not a string.
     * @return SearchLog Chainable
     */
    public function setKeyword($kw)
    {
        if (!is_string($kw)) {
            throw new InvalidArgumentException(
                'Keyword must be a string'
            );
        }

        $this->keyword = $kw;

        return $this;
    }

    /**
     * Retrieve the searched term.
     *
     * @return string
     */
    public function keyword()
    {
        return $this->keyword;
    }

    /**
     * Set the options applied to the search.
     *
     * @param  mixed $options The search options, if defined.
     * @throws InvalidArgumentException If the options is not an array or invalid JSON.
     * @return SearchLog Chainable
     */
    public function setOptions($options)
    {
        if ($options === null) {
            $this->options = null;
        } elseif (is_string($options)) {
            $this->options = json_decode($options, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidArgumentException(
                    sprintf('Invalid JSON for search options: "%s"', $options)
                );
            }
        } elseif (is_array($options)) {
            $this->options = $options;
        } else {
            throw new InvalidArgumentException(
                'Invalid search options. Must be a JSON string, an array, or NULL.'
            );
        }

        return $this;
    }

    /**
     * Retrieve the options applied to the search.
     *
     * @return array
     */
    public function options()
    {
        return $this->options;
    }

    /**
     * Set the result count.
     *
     * @param  integer $count The number of results from the search.
     * @return SearchLog Chainable
     */
    public function setNumResults($count)
    {
        $this->numResults = (int)$count;

        return $this;
    }

    /**
     * Retrieve the result count.
     *
     * @return integer
     */
    public function numResults()
    {
        return $this->numResults;
    }

    /**
     * Set the collection of results.
     *
     * @param  mixed $results The search results data, if available.
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
        } elseif ($results instanceof Traversable) {
            $this->results = iterator_to_array($results, false);
        } else {
            throw new InvalidArgumentException(
                'Invalid search results type. Must be a JSON string, an array, an iterator, or NULL.'
            );
        }

        return $this;
    }

    /**
     * Retrieve the collection of results.
     *
     * @return array
     */
    public function results()
    {
        return $this->results;
    }

    /**
     * Set the client session ID.
     *
     * @param  string $id The session identifier. Typically, {@see session_id()}.
     * @throws InvalidArgumentException If the session id is not a string.
     * @return SearchLog Chainable
     */
    public function setSessionId($id)
    {
        if ($id === null) {
            $this->sessionId = null;
            return $this;
        }

        if (!is_string($id)) {
            throw new InvalidArgumentException(
                'The session ID must be a string.'
            );
        }

        $this->sessionId = $id;

        return $this;
    }

    /**
     * Retrieve the client session ID.
     *
     * @return string
     */
    public function sessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set the client IP address.
     *
     * @param  integer|null $ip The remote IP at object creation.
     * @return SearchLog Chainable
     */
    public function setIp($ip)
    {
        if ($ip === null) {
            $this->ip = null;
            return $this;
        }

        if (is_string($ip)) {
            $ip = ip2long($ip);
        } elseif (is_numeric($ip)) {
            $ip = (int)$ip;
        } else {
            $ip = 0;
        }

        $this->ip = $ip;

        return $this;
    }

    /**
     * Retrieve the client IP address.
     *
     * @return integer|null
     */
    public function ip()
    {
        return $this->ip;
    }

    /**
     * Set the origin language.
     *
     * @param  string $lang The language code.
     * @throws InvalidArgumentException If the argument is not a string.
     * @return SearchLog Chainable
     */
    public function setLang($lang)
    {
        if ($lang !== null) {
            if (!is_string($lang)) {
                throw new InvalidArgumentException(
                    'Language must be a string'
                );
            }
        }

        $this->lang = $lang;

        return $this;
    }

    /**
     * Retrieve the language.
     *
     * @return string
     */
    public function lang()
    {
        return $this->lang;
    }

    /**
     * Set the origin of the search request.
     *
     * @param  string $origin The source URL or identifier of the submission.
     * @throws InvalidArgumentException If the argument is not a string.
     * @return SearchLog Chainable
     */
    public function setOrigin($origin)
    {
        if ($origin !== null) {
            if (!is_string($origin)) {
                throw new InvalidArgumentException(
                    'Origin must be a string.'
                );
            }
        }

        $this->origin = $origin;

        return $this;
    }

    /**
     * Resolve the origin of the search.
     *
     * @return string
     */
    public function resolveOrigin()
    {
        $uri = 'http';

        if (getenv('HTTPS') === 'on') {
            $uri .= 's';
        }

        $uri .= '://';
        $uri .= getenv('HTTP_HOST').getenv('REQUEST_URI');

        return $uri;
    }

    /**
     * Retrieve the origin of the search request.
     *
     * @return string
     */
    public function origin()
    {
        return $this->origin;
    }

    /**
     * Set when the search was initiated.
     *
     * @param  DateTime|string|null $timestamp The timestamp of search request.
     *     NULL is accepted and instances of DateTimeInterface are recommended;
     *     any other value will be converted (if possible) into one.
     * @throws InvalidArgumentException If the timestamp is invalid.
     * @return SearchLog Chainable
     */
    public function setTs($timestamp)
    {
        if ($timestamp === null) {
            $this->ts = null;
            return $this;
        }

        if (is_string($timestamp)) {
            try {
                $timestamp = new DateTime($timestamp);
            } catch (Exception $e) {
                throw new InvalidArgumentException(
                    sprintf('Invalid timestamp: %s', $e->getMessage())
                );
            }
        }

        if (!$timestamp instanceof DateTimeInterface) {
            throw new InvalidArgumentException(
                'Invalid timestamp value. Must be a date/time string or a DateTime object.'
            );
        }

        $this->ts = $timestamp;

        return $this;
    }

    /**
     * Retrieve the creation timestamp.
     *
     * @return DateTime|null
     */
    public function ts()
    {
        return $this->ts;
    }

    /**
     * Event called before _creating_ the object.
     *
     * @see    Charcoal\Source\StorableTrait::preSave() For the "create" Event.
     * @return boolean
     */
    public function preSave()
    {
        $result = parent::preSave();

        $this->setTs('now');

        if (session_id()) {
            $this->setSessionId(session_id());
        }

        if (getenv('REMOTE_ADDR')) {
            $this->setIp(getenv('REMOTE_ADDR'));
        }

        if (!isset($this->origin)) {
            $this->setOrigin($this->resolveOrigin());
        }

        return $result;
    }
}
