<?php

namespace Ejsmont\CircuitBreakerBundle\Tests\Unit;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ejsmont\CircuitBreaker\Core\CircuitBreaker;

class DoctrineCacheAdapterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ContainerBuilder
     */
    private $container;

    public function setup() {
        parent::setUp();
        $this->container = new ContainerBuilder();
        $this->container->setParameter("kernel.cache_dir", dirname(__FILE__) . "/../../build/cache");
    }

    public function testBundle() {
        $inst = new \Ejsmont\CircuitBreakerBundle\EjsmontCircuitBreakerBundle();
        $this->assertTrue(!empty($inst));
    }

    public function testServiceXml() {
        $inst = new \Ejsmont\CircuitBreakerBundle\DependencyInjection\EjsmontCircuitBreakerExtension();
        $this->assertEquals('ejsmont_circuit_breaker', $inst->getAlias());
    }

    public function testLoadApcExtension() {
        $inst = new \Ejsmont\CircuitBreakerBundle\DependencyInjection\EjsmontCircuitBreakerExtension();
        $inst->load(array(), $this->container);
        $cb = $this->container->get('apcCircuitBreaker');

        $this->assertTrue($cb instanceof CircuitBreaker);
    }

    public function testLoadDefaultExtension() {
        $inst = new \Ejsmont\CircuitBreakerBundle\DependencyInjection\EjsmontCircuitBreakerExtension();
        $inst->load(array(), $this->container);
        $cb = $this->container->get('circuitBreaker');
        $this->assertTrue($cb instanceof CircuitBreaker);

        // before its all ok
        $this->assertTrue($cb->isAvailable('testFakeService'));
        // mark as failed
        for ($i = 1; $i <= 30; $i++) {
            $cb->reportFailure('testFakeService');
        }
        // after fails but others work
        $this->assertFalse($cb->isAvailable('testFakeService'));
        $this->assertTrue($cb->isAvailable('anotherFakeService'));
    }

}

