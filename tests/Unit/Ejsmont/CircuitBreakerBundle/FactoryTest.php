<?php

namespace Tests\Unit\Ejsmont\CircuitBreakerBundle;

use Ejsmont\CircuitBreakerBundle\Factory;
use Ejsmont\CircuitBreaker\Core\CircuitBreaker;

class FactoryTest extends \PHPUnit_Framework_TestCase {

    public function testCacheFactory() {
        $doctrineCache = $this->getMock('Doctrine\Common\Cache\Cache', array(), array(), '', false);
        $doctrineCache->expects($this->once())->method("fetch")->will($this->returnValue(null));

        $inst = Factory::getDoctrineCacheInstance($doctrineCache, 2, 900);

        $this->assertTrue($inst instanceof CircuitBreaker);
        $this->assertTrue($inst->isAvailable("a"));
    }

}