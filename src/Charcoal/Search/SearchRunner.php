<?php

namespace Charcoal\Search;

use \Exception;
use \InvalidArgumentException;

use \Psr\Log\LoggerAwareInterface;
use \Psr\Log\LoggerAwareTrait;

use \Charcoal\Factory\FactoryInterface;

use \Charcoal\Search\SearchConfig;
use \Charcoal\Search\SearchLog;
use \Charcoal\Search\SearchRunnerInterface;

/**
 *
 */
class SearchRunner implements SearchRunnerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var FactoryInterface $modelFactory
     */
    private $modelFactory;

    /**
     * @var SearchConfig $searchConfig
     */
    private $searchConfig;

    /**
     * @var SearchLog $searchLog
     */
    private $searchLog;

    /**
     * @var array $results
     */
    private $results;

    /**
     * @param array $data The constructor options.
     * @return void
     */
    public function __construct(array $data)
    {
        $this->setSearchConfig($data['search_config']);
        $this->setModelFactory($data['model_factory']);
        $this->setLogger($data['logger']);
    }

    /**
     * @param FactoryInterface $factory The factory used to create logs and models.
     * @return void
     */
    private function setModelFactory(FactoryInterface $factory)
    {
        $this->modelFactory = $factory;
    }

    /**
     * @throws Exception If the model factory was not properly set.
     * @return FactoryInterface
     */
    protected function modelFactory()
    {
        if ($this->modelFactory === null) {
            throw new Exception(
                'Can not access model factory, the dependency has not been set.'
            );
        }
        return $this->modelFactory;
    }

    /**
     * @param array|SearchConfig $searchConfig The search options / configuration.
     * @return  SearchRunner Chainable
     */
    protected function setSearchConfig($searchConfig)
    {
        if (!($searchConfig instanceof SearchConfig)) {
            $searchConfig = new SearchConfig($searchConfig);
        }
        $this->searchConfig = $searchConfig;
        return $this;
    }

    /**
     * Public access to the search config.
     * @return SearchConfig
     */
    public function searchConfig()
    {
        return $this->searchConfig;
    }

    /**
     * Public access to the search log.
     * @return SearchLog
     */
    public function searchLog()
    {
        return $this->searchLog;
    }

    /**
     * @return array
     */
    public function results()
    {
        return $this->results;
    }

    /**
     * @param string $keyword The searched keyword.
     * @throws InvalidArgumentException If the keyword is not a string.
     * @return array The results.
     */
    final public function search($keyword)
    {
        if (!is_string($keyword)) {
            throw new InvalidArgumentException(
                'Search keyword must be a string.'
            );
        }

        if ($keyword == '') {
            throw new InvalidArgumentException(
                'Keyword can not be empty.'
            );
        }

        // Reset results
        $this->results = [];

        $searchConfig = $this->searchConfig();

        $log = $this->modelFactory()->create(SearchLog::class);
        $log->setData([
            'search_ident'  => isset($searchConfig['ident']) ? $searchConfig['ident'] : '',
            'keyword'       => $keyword
        ]);

        if (!isset($searchConfig['objects'])) {
            throw new InvalidArgumentException(
                'No objects defined in search config.'
            );
        }

        $numResults = 0;
        $searchObjects = $searchConfig['objects'];

        foreach ($searchObjects as $searchIdent => $searchObj) {
            $results = $this->runSearch($keyword, $searchObj);
            $this->results[$searchIdent] = $results;
            $numResults += count($results);
        }

        $log->setNumResults($numResults);
        $log->save();
        $this->searchLog = $log;

        return $this->results;
    }

    /**
     * @param string $keyword       The searched keyword.
     * @param array  $searchOptions The search options.
     * @throws InvalidArgumentException If the search type is undefined or invalid.
     * @return array The results.
     */
    final private function runSearch($keyword, array $searchOptions)
    {
        if (!isset($searchOptions['search_type'])) {
            throw new InvalidArgumentException(
                'Invalid search options. Must have a search type defined.'
            );
        }
        $searchType = $searchOptions['search_type'];

        if ($searchType === 'custom') {
            return $this->runCustomSearch($keyword, $searchOptions);
        } elseif ($searchType === 'table') {
            return $this->runTableSearch($keyword, $searchOptions);
        } elseif ($searchType == 'model') {
            return $this->runModelSearch($keyword, $searchOptions);
        } else {
            throw new InvalidArgumentException(
                'Invalid search options. Search type can be "custom", "table" or "model".'
            );
        }
    }

    /**
     * @param string $keyword       The searched keyword.
     * @param array  $searchOptions The search options.
     * @throws InvalidArgumentException If the callback search option is not defined.
     * @return array The results.
     */
    final private function runCustomSearch($keyword, array $searchOptions)
    {
        if (!isset($searchOptions['callback'])) {
            throw new InvalidArgumentException(
                'Invalid custom search: no callback defined'
            );
        }

        $callback = $searchOptions['callback'];
        if (is_callable($callback)) {
            return $callback($keyword);
        } elseif (is_callable([$this, $callback])) {
            return $this->{$callback}($keyword);
        } else {
            throw new InvalidArgumentException(
                'Invalid custom search: callback method can not be called.'
            );
        }
    }

    /**
     * @param string $keyword       The searched keyword.
     * @param array  $searchOptions The search options.
     * @return array The results.
     */
    final private function runTableSearch($keyword, array $searchOptions)
    {
        // Not implemented.
        return [];
    }

    /**
     * @param string $keyword       The searched keyword.
     * @param array  $searchOptions The search options.
     * @return array The results.
     */
    final private function runModelSearch($keyword, array $searchOptions)
    {
        // Not implemented.
        return [];
    }
}
