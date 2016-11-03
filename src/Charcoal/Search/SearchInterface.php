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
     * @param  string $keyword       The search term(s).
     * @param  array  $searchOptions Additional options.
     * @return array|\Traversable The results.
     */
    public function search($keyword, array $searchOptions = []);

    /**
     * Alias of {@see self::search()}.
     *
     * @param  string $keyword       The search term(s).
     * @param  array  $searchOptions Additional options.
     * @return array The results.
     */
    public function __invoke($keyword, array $searchOptions = []);

    /**
     * Retrieve the results from the latest search.
     *
     * @param  string $resultType The type of results to retrieve.
     * @return array The results.
     */
    public function lastResults($resultType = 'raw');
}
