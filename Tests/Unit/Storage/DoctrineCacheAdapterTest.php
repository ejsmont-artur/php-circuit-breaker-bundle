<?php

namespace Ejsmont\CircuitBreakerBundle\Tests\Unit\Storage;

use Ejsmont\CircuitBreakerBundle\Storage\DoctrineCacheAdapter;
use Doctrine\Common\Cache\ArrayCache;

class DoctrineCacheAdapterTest extends \PHPUnit_Framework_TestCase {

    public function testArrayForce() {
        $cache = new ArrayCache();
        $adapter = new DoctrineCacheAdapter($cache);

        $adapter->saveStatus('a', 'b', 123, true);
        $res = $adapter->loadStatus('a', 'b');

        $this->assertEquals(123, $res);
    }

    public function testArrayNonForce() {
        $cache = new ArrayCache();
        $adapter = new DoctrineCacheAdapter($cache);

        $adapter->saveStatus('a', 'b', 123, false);
        $res = $adapter->loadStatus('a', 'b');

        $this->assertEquals(123, $res);
    }

}