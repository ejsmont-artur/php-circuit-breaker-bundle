# Introduction to php-circuit-breaker-bundle

[![Build Status](https://travis-ci.org/ejsmont-artur/php-circuit-breaker-bundle.png?branch=master)](https://travis-ci.org/ejsmont-artur/php-circuit-breaker-bundle)

php-circuit-breaker-bundle is a [Symfony 2](https://github.com/symfony/symfony) bundle, providing easy integration of [php-circuit-breaker](https://github.com/ejsmont-artur/php-circuit-breaker) component.

[php-circuit-breaker](https://github.com/ejsmont-artur/php-circuit-breaker) is the core package providing a generic 
PHP implementation of [circuit breaker pattern](http://artur.ejsmont.org/blog/circuit-breaker). This bundle wraps it up 
and makes easier to use with [Symfony 2](https://github.com/symfony/symfony) framework.
Bundle uses service.xml to configure default services. It also integrates with Doctrine/Cache to allow you to use any 
cache backend (in case you were already using Doctrine/Cache).

## Motivation & Benefits

* Easy use of circuit breaker withing symfony2 applications.

## Example 1 default APC storage

This is the simplest example as you use defaults for all settings and default APC storage.
Circuit breaker status information will be serialised into APC cache.

    $circuitBreaker = $this->get('apcCircuitBreaker');

Yes, that is it. You can use predefined service called apcCircuitBreaker and you do not need any settings.
It will use static factory and keep returning the same instance during script run.

## Example 2 Custom cache and settings

    TBD

## Example 3 Manual composition

If you wanted to do it for some reason you can also create instance of circuit breaker by hand.
In this example we use Doctrine\Cache adapter so all you need to provide is the cache instance.
In this case, just to mix it up, we have decided to use file cache.

    $fileCache = new \Doctrine\Common\Cache\FilesystemCache('/tmp/cache/', '.cache');
    $circuitBreaker = Factory::getDoctrineCacheInstance($fileCache);

## Examples continuation

See more details of Circuit Breaker pattern here:
https://github.com/ejsmont-artur/php-circuit-breaker
http://artur.ejsmont.org/blog/circuit-breaker

In short, once you get instance of circuit breaker you can ask it if particular service is available or not.
Circuit breaker will check its status metrics and give you response based on its previous records.
After success you tell Circuit Breaker it went ok, in case of service failure you report service being down.
This way Circuit breaker learns what is the status of each service (names are arbitrary strings).
You can define threshold and retry timeout so in case of failure it will let you retry from time to time.

    if ($circuitBreaker->isAvailable("UserProfileService1")) {
        try{
            // do whatever necessary
            $circuitBreaker->reportSuccess('UserProfileService1');
        }catch(ServiceCallFailedServiceDown $e){
            $circuitBreaker->reportFailure('UserProfileService1');
        }
    }else{
        // show temporarily unavailable message
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
