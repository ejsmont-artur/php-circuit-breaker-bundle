<?php

namespace Tests\Unit\Ejsmont\CircuitBreakerBundle;

class DoctrineCacheAdapterTest extends \PHPUnit_Framework_TestCase {

    public function testBundle() {
        $inst = new \Ejsmont\CircuitBreakerBundle\EjsmontCircuitBreakerBundle();
        $this->assertTrue(!empty($inst));
    }

    public function testServiceXml() {
        $inst = new \Ejsmont\CircuitBreakerBundle\DependencyInjection\EjsmontCircuitBreakerExtension();
        $this->assertEquals('ejsmont_circuit_breaker', $inst->getAlias());
    }

}