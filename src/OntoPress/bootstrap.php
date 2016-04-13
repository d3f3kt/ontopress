<?php

$classLoader = require __DIR__.'/../../vendor/autoload.php';

use OntoPress\Library\AppKernel;

if (getenv('ontopress_env') == 'test') {
    $kernel = new AppKernel('test', $classLoader);
} else {
    $kernel = new AppKernel('dev', $classLoader);
}

return $kernel->getContainer();
