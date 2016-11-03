<?php

namespace Charcoal\Search;

use \RuntimeException;

use \Psr\Log\LoggerAwareInterface;
use \Psr\Log\LoggerAwareTrait;

use \Charcoal\Factory\FactoryInterface;

/**
 * A Basic Search Request
 *
 * Abstract implementation of {@see \Charcoal\Search\SearchInterface}.
 */
abstract class AbstractSearch implements
    SearchInterface,
    LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * The (raw) search results.
     *
     * @var array
     */
    protected $results;

    /**
     * Store the factory instance for the current class.
     *
     * @var FactoryInterface
     */
    private $modelFactory;

    /**
     * Return a new search object.
     *
     * @param array|\ArrayAccess $data The class options and dependencies.
     */
    public function __construct($data)
    {
        $this->setLogger($data['logger']);
        $this->setModelFactory($data['model_factory']);
    }

    /**
     * Set an object model factory.
     *
     * @param  FactoryInterface $factory The model factory, to create objects.
     * @return self
     */
    protected function setModelFactory(FactoryInterface $factory)
    {
        $this->modelFactory = $factory;

        return $this;
    }

    /**
     * Retrieve the object model factory.
     *
     * @throws RuntimeException If the model factory was not previously set.
     * @return FactoryInterface
     */
    public function modelFactory()
    {
        if (!isset($this->modelFactory)) {
            throw new RuntimeException(
                sprintf('Model Factory is not defined for "%s"', get_class($this))
            );
        }

        return $this->modelFactory;
    }

    /**
     * Process the search query.
     *
     * @param  string $keyword       The search term(s).
     * @param  array  $searchOptions Additional options.
     * @return array|\Traversable The results.
     */
    abstract public function search($keyword, array $searchOptions = []);

    /**
     * Alias of {@see self::search()}.
     *
     * A search is always callable.
     *
     * @param  string $keyword       The search term(s).
     * @param  array  $searchOptions Additional options.
     * @return array The results.
     */
    final public function __invoke($keyword, array $searchOptions = [])
    {
        return $this->search($keyword, $searchOptions);
    }

    /**
     * Retrieve the results from the latest search.
     *
     * @param  string $resultType The type of results to search. Can be only "raw" for now.
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
            return $this->results;
        }

        return [];
    }

    /**
     * Determine if the latest search has any results.
     *
     * @return boolean
     */
    final public function hasResults()
    {
        return boolval($this->results);
    }

    /**
     * Set the results from a search.
     *
     * @param  array $results The (raw) search results.
     * @return void
     */
    final protected function setResults(array $results)
    {
        $this->results = $results;
    }
}
