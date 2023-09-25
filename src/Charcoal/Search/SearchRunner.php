<?php

namespace Charcoal\Search;

use Exception;
use InvalidArgumentException;

// From PSR-3
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

// From 'charcoal-factory'
use Charcoal\Factory\FactoryInterface;

/**
 * A basic search mediator
 */
class SearchRunner implements SearchRunnerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var FactoryInterface $modelFactory
     */
    private $modelFactory;

    /**
     * @var SearchRunnerConfig $searchConfig
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
     * @var boolean $logDisabled
     */
    public $logDisabled = false;

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
     * @param array|SearchRunnerConfig $searchConfig The search options / configuration.
     * @return  SearchRunner Chainable
     */
    protected function setSearchConfig($searchConfig)
    {
        if (!($searchConfig instanceof SearchRunnerConfig)) {
            $searchConfig = new SearchRunnerConfig($searchConfig);
        }
        $this->searchConfig = $searchConfig;
        return $this;
    }

    /**
     * Public access to the search config.
     * @return SearchRunnerConfig
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
     * @param  string $keyword       The searched query.
     * @param  array  $searchOptions Optional settings passed to each search objects.
     * @param  array  $logOptions    Optional data passed to the search log.
     * @throws InvalidArgumentException If the query is not a string.
     * @return array The results.
     */
    final public function search($keyword, array $searchOptions = [], array $logOptions = [])
    {
        if (!is_string($keyword)) {
            throw new InvalidArgumentException(
                'Search query must be a string.'
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

        if (!isset($searchConfig['searches'])) {
            throw new InvalidArgumentException(
                'No searches defined in search config.'
            );
        }

        $numResults    = 0;
        $searchObjects = $searchConfig['searches'];
        $searchDeps    = [
            'logger'        => $this->logger,
            'model_factory' => $this->modelFactory()
        ];

        foreach ($searchObjects as $searchIdent => $searchObj) {
            if ($searchObj instanceof SearchInterface) {
                $results = $searchObj->search($keyword, $searchOptions);
            } else {
                $search  = new CustomSearch(array_merge($searchObj, $searchDeps));
                $results = $search->search($keyword, $searchOptions);
            }

            $this->results[$searchIdent] = $results;
            $numResults += count($results);
        }

        $logData = [
            'search_ident'    => isset($searchConfig['ident']) ? $searchConfig['ident'] : '',
            'search_options'  => $searchOptions,
            'keyword'         => $keyword,
            'num_results'     => $numResults
        ];

        if ($logOptions) {
            $logOptions = array_diff_key($logOptions, array_keys($logData));
            $logData    = array_merge($logData, $logOptions);
        }

        $this->searchLog = $this->createLog($logData);

        return $this->results;
    }

    /**
     * @param array $logData Log data.
     * @return SearchLog
     */
    private function createLog(array $logData)
    {
        $log = $this->modelFactory()->create(SearchLog::class);
        $log->setData($logData);

        if ($this->logDisabled === false) {
            $log->save();
        }

        return $log;
    }
}
