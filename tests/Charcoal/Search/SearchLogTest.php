<?php

namespace Charcoal\Tests\Search;

use InvalidArgumentException;

// From PSR-3
use Psr\Log\NullLogger;

// From 'cache/void-adapter'
use Cache\Adapter\Void\VoidCachePool;

// From 'charcoal-core'
use Charcoal\Model\Service\MetadataLoader;

// From 'charcoal-search'
use Charcoal\Search\SearchLog;
use Charcoal\Tests\AbstractTestCase;

/**
 *
 */
class SearchLogTest extends AbstractTestCase
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
            'logger'    => new NullLogger(),
            'base_path' => __DIR__,
            'paths'     => [ 'metadata' ],
            'cache'     => new VoidCachePool()
        ]);

        $this->obj = new SearchLog([
            'logger'          => new NullLogger(),
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

        $this->expectException(InvalidArgumentException::class);
        $this->obj->setSearchIdent(false);
    }

    public function testSetKeyword()
    {
        $ret = $this->obj->setKeyword('bar');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals('bar', $this->obj->keyword());

        $this->expectException(InvalidArgumentException::class);
        $this->obj->setKeyword(false);
    }

    public function testSetOptions()
    {
        $this->assertNull($this->obj->options());

        $ret = $this->obj->setOptions(['foo'=>[1,2,3]]);
        $this->assertSame($ret, $this->obj);
        $this->assertEquals(['foo'=>[1,2,3]], $this->obj->options());

        $this->obj->setOptions(null);
        $this->assertNull($this->obj->options());

        $this->expectException(InvalidArgumentException::class);
        $this->obj->setOptions(false);
    }

    public function testSetNumResults()
    {
        $ret = $this->obj->setNumResults(42);
        $this->assertSame($ret, $this->obj);
        $this->assertEquals(42, $this->obj->numResults());

        $this->obj->setNumResults('666');
        $this->assertEquals(666, $this->obj->numResults());
    }

    public function testSetTs()
    {
        $this->assertNull($this->obj->ts());

        $ret = $this->obj->setTs('2015-01-01 12:13:14');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals(new \DateTime('2015-01-01 12:13:14'), $this->obj->ts());

        $this->obj->setTs(null);
        $this->assertNull($this->obj->ts());

        $this->expectException('\Exception');
        $this->obj->setTs('invalid date');
    }

    public function testSetIp()
    {
        $ret = $this->obj->setIp('127.0.0.1');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals(ip2long('127.0.0.1'), $this->obj->ip());
    }

    public function testSetSessionId()
    {
        $this->assertNull($this->obj->sessionId());
        $ret = $this->obj->setSessionId('foobar');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals('foobar', $this->obj->sessionId());

        $this->obj->setSessionId(null);
        $this->assertNull($this->obj->sessionId());

        $this->expectException(InvalidArgumentException::class);
        $this->obj->setSessionId(false);
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

        $this->expectException(InvalidArgumentException::class);
        $this->obj->setOrigin(false);
    }
}
