<?php

namespace Charcoal\Tests\Search;

use InvalidArgumentException;

// From PSR-3
use Psr\Log\NullLogger;

// From 'cache/void-adapter'
use Cache\Adapter\Void\VoidCachePool;

// From 'charcoal-factory'
use Charcoal\Factory\GenericFactory;

// From 'charcoal-core'
use Charcoal\Model\Service\MetadataLoader;

// From 'charcoal-search'
use Charcoal\Search\SearchRunnerConfig;
use Charcoal\Search\SearchRunner;
use Charcoal\Tests\AbstractTestCase;

/**
 *
 */
class SearchRunnerTest extends AbstractTestCase
{
    /**
     * @var \Charcoal\Factory\FactoryInterface $modelFactory
     */
    private $modelFactory;

    /**
     * An instance of the SearchRunnerh object under test
     * @var SearchRunner $obj
     */
    private $obj;

    /**
     * @return \Charcoal\Factory\FactoryInterface
     */
    private function modelFactory()
    {
        if (!$this->modelFactory) {
            $metadataLoader = new MetadataLoader([
                'logger' => new NullLogger(),
                'base_path' => __DIR__,
                'paths' => ['metadata'],
                'cache'  => new VoidCachePool()
            ]);

            $this->modelFactory = new GenericFactory([
                'arguments'     => [[
                    'logger'            => new NullLogger(),
                    'metadata_loader'   => $metadataLoader
                ]]
            ]);
        }
        return $this->modelFactory;
    }

    /**
     * @return SearchRunner
     */
    private function obj($searchConfig = [])
    {
        $this->obj = new SearchRunner([
            'search_config' => $searchConfig,
            'model_factory' => $this->modelFactory(),
            'logger'        => new NullLogger()
        ]);
        // Do not save logs in testing.
        $this->obj->logDisabled = true;
        return $this->obj;
    }

    /**
     * @return array
     */
    private function defaultSearches()
    {
        return [
            'foo' => [
                'search_type' => 'custom',
                'callback' => function($kw) {
                    return ['test'];
                }
            ],
            'bar' => [
                'search_type' => 'custom',
                'callback' => function($kw) {
                    return ['foo', 'bar', 'baz'];
                }
            ]
        ];
    }

    /**
     *
     */
    public function testSearchConfig()
    {
        $obj = $this->obj([
            'foo' => 'bar'
        ]);
        $this->assertInstanceOf(SearchRunnerConfig::class, $obj->searchConfig());
        $this->assertEquals('bar', $obj->searchConfig()['foo']);
    }

    /**
     *
     */
    public function testSearchInvalidKeywordThrowsException()
    {
        $obj = $this->obj();
        $this->expectException(InvalidArgumentException::class);
        $obj->search([]);
    }

    /**
     *
     */
    public function testSearchEmptyKeywordThrowsException()
    {
        $obj = $this->obj();
        $this->expectException(InvalidArgumentException::class);
        $obj->search('');
    }

    /**
     *
     */
    public function testSearchWithoutObjectsThrowsException()
    {
        $obj = $this->obj([
            'foo' => 'nar'
        ]);
        $this->expectException(InvalidArgumentException::class);
        $obj->search('foo');
    }

    /**
     *
     */
    public function testSearchWithoutSearchTypeThrowsException()
    {
        $obj = $this->obj([
            'searches' => [
                'foo'   => []
            ]
        ]);
        $this->expectException(InvalidArgumentException::class);
        $obj->search('foo');
    }

    /**
     *
     */
    public function testSarchInvalidSearchTypeThrowsException()
    {
        $obj = $this->obj([
            'searches' => [
                'foo'   => [
                    'search_type' => '_invalid'
                ]
            ]
        ]);
        $this->expectException(InvalidArgumentException::class);
        $obj->search('foo');
    }

    /**
     *
     */
    public function testSearchCustomWithoutCallbackThrowsException()
    {
        $obj = $this->obj([
            'searches'   =>[
                'foo'       => [
                    'search_type' => 'custom'
                ]
            ]
        ]);
        $this->expectException(InvalidArgumentException::class);
        $obj->search('foo');
    }

    /**
     *
     */
    public function testSearchCustom()
    {
        $obj = $this->obj([
            'searches'   => $this->defaultSearches()
        ]);

        $expected = [
            'foo'   => ['test'],
            'bar'   => ['foo','bar', 'baz']
        ];

        $res = $obj->search('foo');
        $this->assertEquals($expected, $res);
        $this->assertEquals($expected, $obj->results());
        $this->assertEquals(4, $obj->searchLog()->numResults());
    }

    /**
     *
     */
    public function testSearchIdentLogsIdent()
    {
        $obj = $this->obj([
            'ident'     => 'foobar',
            'searches'   => $this->defaultSearches()
        ]);

        $res = $obj->search('foo');
        $this->assertEquals('foobar', $obj->searchLog()->searchIdent());
    }
}
