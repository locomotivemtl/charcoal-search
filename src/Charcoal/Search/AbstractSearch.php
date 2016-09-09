<?php

namespace Charcoal\Search;

use \RuntimeException;

use \Psr\Log\LoggerAwareInterface;
use \Psr\Log\LoggerAwareTrait;

/**
 *
 */
abstract class AbstractSearch implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * The (raw) search results.
     * @var array $results
     */
    private $results;

    /**
     * @param array $data Class options and dependencies.
     */
    public function __construct(array $data)
    {
        $this->setLogger($data['logger']);
    }

    /**
     * @param string $keyword The searched-for keyword.
     * @return array
     */
    abstract public function search($keyword);

    /**
     * A search is always callable (alias to the "search" method).
     *
     * @param string $keyword The searched-for keyword.
     * @return array The results.
     */
    final public function __invoke($keyword)
    {
        return $this->search($keyword);
    }

    /**
     * @param string $resultType The type of results to search. Can be only "raw" for now.
     * @throws RuntimeException If this method is called before a search was executed.
     * @return array The results from the last search operation.
     */
    final public function lastResults($resultType = 'raw')
    {
        if ($this->results === null) {
            throw new RuntimeException(
                'Search was never performed'
            );
        }

        if ($resultType === 'raw') {
            return $this->lastResults;
        }
    }

    /**
     * @param array $results The (raw) search results.
     * @return void
     */
    final protected function setResults(array $results)
    {
        $this->results = $results;
    }
}
