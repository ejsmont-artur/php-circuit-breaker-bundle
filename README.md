# What is php-circuit-breaker-bundle

[![Build Status](https://travis-ci.org/ejsmont-artur/php-circuit-breaker-bundle.png?branch=master)](https://travis-ci.org/ejsmont-artur/php-circuit-breaker-bundle)

[![knpbundles.com](http://knpbundles.com/ejsmont-artur/php-circuit-breaker-bundle/badge-short)](http://knpbundles.com/ejsmont-artur/php-circuit-breaker-bundle)

php-circuit-breaker-bundle is a [Symfony 2](https://github.com/symfony/symfony) bundle, providing easy integration of [php-circuit-breaker](https://github.com/ejsmont-artur/php-circuit-breaker) component.

[php-circuit-breaker](https://github.com/ejsmont-artur/php-circuit-breaker) is the core package providing a generic 
PHP implementation of [circuit breaker pattern](http://artur.ejsmont.org/blog/circuit-breaker). This bundle wraps it up 
and makes easier to use with [Symfony 2](https://github.com/symfony/symfony) framework.
Bundle uses service.xml to configure default services. It also integrates with Doctrine/Cache to allow you to use any 
cache backend (in case you were already using Doctrine/Cache).

# Motivation & Benefits

* Easy use of circuit breaker withing [Symfony 2](https://github.com/symfony/symfony) applications.

# Installation

Since [Symfony 2](https://github.com/symfony/symfony) uses [Composer](http://getcomposer.org/), all you have to do is add a require dependency to your composer.json

    "require": {
        "ejsmont-artur/php-circuit-breaker-bundle": "0.1.*"
    },

Then you can override defaults of threshold and timeout in your application services.yaml

    parameters:
        # Allowed amount of failures before marking service as unavailable
        ejsmont_circuit_breaker.threshold: 3
        # how many seconds should we wait before allowing a single request
        ejsmont_circuit_breaker.retry_timeout: 5

After that you should update composer dependencies and you are good to go.

# Examples

Below you can see a few ways of obtaining instances of circuit breaker component. You can also see how to use it once
you get an instance. For more documentation please see [php-circuit-breaker](https://github.com/ejsmont-artur/php-circuit-breaker) page.

## Example 1 - default APC storage

This is the simplest example as you use defaults for all settings and default APC storage.
Circuit breaker status information will be serialised into APC cache.

    $circuitBreaker = $this->get('apcCircuitBreaker');

Yes, that is it. You can use predefined service called apcCircuitBreaker and you do not need any settings.
It will use static factory and keep returning the same instance during script run.

## Example 2 - configuration via dependency injection

Beside of "apcCircuitBreaker" service that uses APC you can use "circuitBreaker" service which is configurable and
allows you to inject any doctrine cache instance.

In the example below i use "circuitBreakerCacheBackend" service to override the default behaviour of "circuitBreaker" 
service. Here i am using memcached but it could be any doctrine cache instance. This service affects behaviour of 
"circuitBreaker" only, "apcCircuitBreaker" service uses its own APC cache instance.

Configure as needed in service.yaml of your app:

    services:
        circuitBreakerCacheBackend:
            class: Doctrine\Common\Cache\MemcachedCache
            calls:
              -   [setMemcached, ["@memcachedInstance"]]
        memcachedInstance:
            class: Memcached
            calls:
                - [addServer, ['127.0.0.1', 11211, 1]]

Then in your code you can use the configurable doctrine cache instance like below:

     $circuitBreaker = $this->get('circuitBreaker');

## Example 3 - manual composition

If you wanted to do it for some reason you can also create instance of circuit breaker by hand.
In this example we use Doctrine\Cache adapter so all you need to provide is the cache instance.
In this case, just to mix it up, we have decided to use file cache.

    $fileCache = new \Doctrine\Common\Cache\FilesystemCache('/tmp/cache/', '.cache');
    $circuitBreaker = Factory::getDoctrineCacheInstance($fileCache);

## Example of how to use an instance

See more details of Circuit Breaker pattern on [php-circuit-breaker](https://github.com/ejsmont-artur/php-circuit-breaker) 
github page and my [blog posts on circuit breaker](http://artur.ejsmont.org/blog/circuit-breaker)

In short, once you get instance of circuit breaker, you can ask it if particular service is available or not.
Circuit breaker will check it's status metrics and give you response based on its previous records.
After a successful connection to the service you should tell Circuit Breaker that it went ok. In case of service 
failure or timeout, you should report failure to circuit breaker.

This way Circuit breaker "learns" what is the current status of each service (names are arbitrary strings).
You can define threshold and retry timeout to allow single request from time to time in case service got fixed.

    if ($circuitBreaker->isAvailable("UserProfileService1")) {
        try{
            // do something useful with the service
            $circuitBreaker->reportSuccess('UserProfileService1');
        }catch(ServiceCallFailedServiceDown $e){
            // if service is down report it back to circuit breaker
            $circuitBreaker->reportFailure('UserProfileService1');
            // handle as temporarily unavailable (or remove some features)
        }
    }else{
        // handle as temporarily unavailable (or remove some features)
    }   

## Running tests

* Tests are run via PHPUnit It is assumed to be installed via PEAR.
* Tests can be ran using phpunit alone or via ant build targets.
* Tests require all dependencies to be present as bundle is expected to by used only in symfony2 apps.
* The "ci" target generate code coverage repor, "phpunit" target does not.

If you dont have composer, get it and download dependencies (creates /vendor folder)

    curl -s http://getcomposer.org/installer | php
    php composer.phar update

You can run all tests using ant:

    ant phpunit

You can run tests, generate coverage and docs:

    ant ci

You can run selected test case by running:

    cd tests
    phpunit Unit/Ejsmont/CircuitBreakerBundle/Storage/DoctrineCacheAdapterTest.php

## Author

* Artur Esjmont (https://github.com/ejsmont-artur) via http://artur.ejsmont.org
