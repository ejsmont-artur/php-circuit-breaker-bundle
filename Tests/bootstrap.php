<?php

require_once __DIR__.'/../vendor/autoload.php';

use Composer\Autoload\ClassLoader;

$loader = new ClassLoader();
$loader->add('Ejsmont\CircuitBreakerBundle', __DIR__);
$loader->register();

return $loader;
