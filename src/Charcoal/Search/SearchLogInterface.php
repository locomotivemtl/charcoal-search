<?php

namespace Charcoal\Search;

use \DateTime;
use \DateTimeInterface;
use \Traversable;
use \InvalidArgumentException;

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
     * Set the searched term.
     *
     * @param  string $kw The searched term / keyword.
     * @return SearchLogInterface Chainable
     */
    public function setKeyword($kw);

    /**
     * Set the options applied to the search.
     *
     * @param  mixed $options The search options, if any.
     * @return SearchLog Chainable
     */
    public function setOptions($options);

    /**
     * Set the result count.
     *
     * @param  integer $count The number of results from the search.
     * @return SearchLogInterface Chainable
     */
    public function setNumResults($count);

    /**
     * Set the collection of results.
     *
     * @param  mixed $results The search results data, if available.
     * @return SearchLogInterface Chainable
     */
    public function setResults($results);

    /**
     * Set the client session ID.
     *
     * @param  string $id The session identifier. Typically, {@see session_id()}.
     * @return SearchLogInterface Chainable
     */
    public function setSessionId($id);

    /**
     * Set the client IP address.
     *
     * @param  integer|null $ip The remote IP at object creation.
     * @return SearchLogInterface Chainable
     */
    public function setIp($ip);

    /**
     * Set the origin language.
     *
     * @param  string $lang The language code.
     * @return SearchLogInterface Chainable
     */
    public function setLang($lang);

    /**
     * Set the origin of the search request.
     *
     * @param  string $origin The source URL or identifier of the submission.
     * @return SearchLogInterface Chainable
     */
    public function setOrigin($origin);

    /**
     * Set when the search was initiated.
     *
     * @param  DateTime|string|null $timestamp The timestamp of search request.
     * @return SearchLogInterface Chainable
     */
    public function setTs($timestamp);
}
