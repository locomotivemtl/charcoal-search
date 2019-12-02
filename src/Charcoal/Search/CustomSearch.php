<?php

namespace Charcoal\Search;

use InvalidArgumentException;

/**
 * Custom search delegates the search to a callbak.
 */
final class CustomSearch extends AbstractSearch
{
    /**
     * @var callable $callback
     */
    private $callback;

    /**
     * @param  array $data The dependencies and options.
     * @throws InvalidArgumentException If the callback option is not set.
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        if (!isset($data['callback'])) {
            throw new InvalidArgumentException(
                'Invalid custom search data: callback is mandatory.'
            );
        }
        $this->setCallback($data['callback']);
    }

    /**
     * @param  callable $callback The callback that will actually run the search.
     * @return void
     */
    public function setCallback(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Process the search query.
     *
     * @param  string $keyword       The search term(s).
     * @param  array  $searchOptions Additional options.
     * @return array The results.
     */
    public function search($keyword, array $searchOptions = [])
    {
        $callback = $this->callback;
        $results  = $callback($keyword, $searchOptions);
        $this->setResults($results);

        return $results;
    }
}
