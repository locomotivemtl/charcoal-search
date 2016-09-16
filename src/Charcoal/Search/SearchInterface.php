<?php

namespace Charcoal\Search;

/**
 * Defines a search request.
 */
interface SearchInterface
{
    /**
     * Process the search query.
     *
     * @param string $keyword The searched-for keyword.
     * @return array The (raw) results.
     */
    public function search($keyword);

    /**
     * Alias of {@see self::search()}.
     *
     * @param string $keyword The searched-for keyword.
     * @return array The (raw) results.
     */
    public function __invoke($keyword);

    /**
     * Retrieve the results from the latest search.
     *
     * @param string $resultType The type of results to retrieve.
     * @return array The results.
     */
    public function lastResults($resultType = 'raw');
}
