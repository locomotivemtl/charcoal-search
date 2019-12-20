<?php

namespace Charcoal\Search;

/**
 * Defines a search meditator
 */
interface SearchRunnerInterface
{
    /**
     * @param  string $keyword       The searched query.
     * @param  array  $searchOptions Optional settings passed to each search objects.
     * @param  array  $logOptions    Optional data passed to the search log.
     * @return array The results.
     */
    public function search($keyword, array $searchOptions = [], array $logOptions = []);
}
