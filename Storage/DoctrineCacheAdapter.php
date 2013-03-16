<?php

namespace Ejsmont\CircuitBreakerBundle\Storage;

use Ejsmont\CircuitBreaker\Storage\Adapter\BaseAdapter;
use Doctrine\Common\Cache\Cache;

/**
 * Class is an adapter between Doctrine\Cache and Ejsmont\CircuitBreaker\Storage
 * 
 * I use instance of cahe provided by your symfony application (doctrine cache)
 */
class DoctrineCacheAdapter extends BaseAdapter {

    /**
     * Instance to be used by the circuit breaker
     * @var \Doctrine\Common\Cache\Cache $doctrineCacheInstance 
     */
    private $doctrineCacheInstance;

    /**
     * Expects doctring cache implementation instance to route 
     * circuit breaker calls. Instance have to be ready to use.
     * 
     * @param \Doctrine\Common\Cache\Cache $doctrineCacheInstance
     */
    public function __construct(Cache $doctrineCacheInstance) {
        $this->doctrineCacheInstance = $doctrineCacheInstance;
    }

    /**
     * If you provided instance of doctrine cache we assume that it is ready to go.
     * @return boolean
     */
    protected function checkExtension() {
        return true;
    }

    /**
     * Loads item by cache key.
     * 
     * @param string $key
     * @return mixed
     * 
     * @throws Ejsmont\CircuitBreaker\Storage\StorageException if storage error occurs, handler can not be used
     */
    protected function load($key) {
        return $this->doctrineCacheInstance->fetch($key);
    }

    /**
     * Save item in the cache.
     * 
     * @param string $key
     * @param string $value
     * @param int $ttl
     * @return void
     * 
     * @throws Ejsmont\CircuitBreaker\Storage\StorageException if storage error occurs, handler can not be used
     */
    protected function save($key, $value, $ttl) {
        $this->doctrineCacheInstance->save($key, $value, $ttl);
    }

}