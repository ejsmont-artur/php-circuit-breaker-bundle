<?php

namespace Tests\Unit\Ejsmont\CircuitBreakerBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ejsmont\CircuitBreaker\Core\CircuitBreaker;

class DoctrineCacheAdapterTest extends \PHPUnit_Framework_TestCase {

    public function testBundle() {
        $inst = new \Ejsmont\CircuitBreakerBundle\EjsmontCircuitBreakerBundle();
        $this->assertTrue(!empty($inst));
    }

    public function testServiceXml() {
        $inst = new \Ejsmont\CircuitBreakerBundle\DependencyInjection\EjsmontCircuitBreakerExtension();
        $this->assertEquals('ejsmont_circuit_breaker', $inst->getAlias());
    }

    public function testLoadApcExtension() {
        $container = new ContainerBuilder();
        $inst = new \Ejsmont\CircuitBreakerBundle\DependencyInjection\EjsmontCircuitBreakerExtension();
        $inst->load(array(), $container);
        $cb = $container->get('apcCircuitBreaker');

        print_r($cb);
        
        $this->assertTrue($cb instanceof CircuitBreaker);
    }

}