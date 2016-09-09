<?php

namespace Charcoal\Search\Tests;

use \PHPUnit_Framework_TestCase;

use \Psr\Log\NullLogger;
use \Cache\Adapter\Void\VoidCachePool;

use \Charcoal\Model\MetadataLoader;

use \Charcoal\Search\SearchLog;

/**
 *
 */
class SearchLogTest extends PHPUnit_Framework_TestCase
{
    /**
     * An instance of the SearchLog object under test
     * @var SearchLog $obj
     */
    private $obj;

    /**
     *
     */
    public function setUp()
    {
        $metadataLoader = new MetadataLoader([
            'logger' => new NullLogger(),
            'base_path' => __DIR__,
            'paths' => ['metadata'],
            'cache'  => new VoidCachePool()
        ]);

        $this->obj = new SearchLog([
            'logger' => new NullLogger(),
            'metadata_loader' => $metadataLoader
        ]);
    }

    /**
     *
     */
    public function testSetSearchIdent()
    {
        $ret = $this->obj->setSearchIdent('foo');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals('foo', $this->obj->searchIdent());

        $this->setExpectedException('\InvalidArgumentException');
        $this->obj->setSearchIdent(false);
    }

    public function testSetKeyword()
    {
        $ret = $this->obj->setKeyword('bar');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals('bar', $this->obj->keyword());

        $this->setExpectedException('\InvalidArgumentException');
        $this->obj->setKeyword(false);
    }

    public function testSetNumResults()
    {
        $ret = $this->obj->setNumResults(42);
        $this->assertSame($ret, $this->obj);
        $this->assertEquals(42, $this->obj->numResults());

        $this->obj->setNumResults('666');
        $this->assertEquals(666, $this->obj->numResults());
    }

    public function testSetResults()
    {
        $this->assertNull($this->obj->results());

        $ret = $this->obj->setResults(['foo'=>[1,2,3]]);
        $this->assertSame($ret, $this->obj);
        $this->assertEquals(['foo'=>[1,2,3]], $this->obj->results());

        $this->obj->setResults(null);
        $this->assertNull($this->obj->results());

        $this->setExpectedException('\InvalidArgumentException');
        $this->obj->setResults(false);
    }

    public function testSetResultsJson()
    {
        $this->obj->setResults('{"foo":[1,2,3]}');
        $this->assertEquals(['foo'=>[1,2,3]], $this->obj->results());

        $this->setExpectedException('\InvalidArgumentException');
        $this->obj->setResults('{"foo:invalid');
    }

    public function testSetTs()
    {
        $this->assertNull($this->obj->ts());

        $ret = $this->obj->setTs('2015-01-01 12:13:14');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals(new \DateTime('2015-01-01 12:13:14'), $this->obj->ts());

        $this->obj->setTs(null);
        $this->assertNull($this->obj->ts());

        $this->setExpectedException('\Error');
        $this->setTs('invalid date');
    }

    public function testSetIp()
    {
        $ret = $this->obj->setIp('127.0.0.1');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals('127.0.0.1', $this->obj->ip());
    }

    public function testSetLang()
    {
        $ret = $this->obj->setLang('fr');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals('fr', $this->obj->lang());
    }

    public function testSetOrigin()
    {
        $ret = $this->obj->setOrigin('foobar');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals('foobar', $this->obj->origin());

        $this->setExpectedException('\InvalidArgumentException');
        $this->obj->setOrigin(false);
    }
}
