<?php

namespace Charcoal\Search;

use DateTime;
use DateTimeInterface;
use Traversable;
use InvalidArgumentException;

/**
 * Defines a search log.
 */
interface SearchLogInterface
{
    /**
     * Set the log's associated search identifier.
     *
     * @param  string $ident The search identifier.
     * @return SearchLogInterface Chainable
     */
    public function setSearchIdent($ident);

    /**
     * Retrieve the log's associated search identifier.
     *
     * @return string
     */
    public function searchIdent();

    /**
     * Set the searched term.
     *
     * @param  string $kw The searched term / keyword.
     * @return SearchLogInterface Chainable
     */
    public function setKeyword($kw);

    /**
     * Retrieve the searched term.
     *
     * @return string
     */
    public function keyword();

    /**
     * Set the options applied to the search.
     *
     * @param  mixed $options The search options, if any.
     * @return SearchLog Chainable
     */
    public function setOptions($options);

    /**
     * Retrieve the options applied to the search.
     *
     * @return array
     */
    public function options();

    /**
     * Set the result count.
     *
     * @param  integer $count The number of results from the search.
     * @return SearchLogInterface Chainable
     */
    public function setNumResults($count);

    /**
     * Retrieve the result count.
     *
     * @return integer
     */
    public function numResults();

    /**
     * Set the client session ID.
     *
     * @param  string $id The session identifier. Typically, {@see session_id()}.
     * @return SearchLogInterface Chainable
     */
    public function setSessionId($id);

    /**
     * Retrieve the client session ID.
     *
     * @return string
     */
    public function sessionId();

    /**
     * Set the client IP address.
     *
     * @param  integer|null $ip The remote IP at object creation.
     * @return SearchLogInterface Chainable
     */
    public function setIp($ip);

    /**
     * Retrieve the client IP address.
     *
     * @return integer|null
     */
    public function ip();

    /**
     * Set the origin language.
     *
     * @param  string $lang The language code.
     * @return SearchLogInterface Chainable
     */
    public function setLang($lang);

    /**
     * Retrieve the language.
     *
     * @return string
     */
    public function lang();

    /**
     * Set the origin of the search request.
     *
     * @param  string $origin The source URL or identifier of the submission.
     * @return SearchLogInterface Chainable
     */
    public function setOrigin($origin);

    /**
     * Retrieve the origin of the search request.
     *
     * @return string
     */
    public function origin();

    /**
     * Set when the search was initiated.
     *
     * @param  DateTime|string|null $timestamp The timestamp of search request.
     * @return SearchLogInterface Chainable
     */
    public function setTs($timestamp);

    /**
     * Retrieve the creation timestamp.
     *
     * @return DateTime|null
     */
    public function ts();
}
