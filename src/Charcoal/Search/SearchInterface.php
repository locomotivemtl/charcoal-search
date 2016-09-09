<?php

namespace Charcoal\Search;

/**
 *
 */
interface SearchInterface
{
    /**
     * @param string $keyword The searched-for keyword.
     * @return array The (raw) results.
     */
    public function search($keyword);

    /**
     * @param string $keyword The searched-for keyword.
     * @return array The (raw) results.
     */
    public function __invoke($keyword);

    /**
     * @param string $resultType The type of results to retrieve.
     * @return array The results.
     */
    public function lastResults($resultType = 'raw');
}
