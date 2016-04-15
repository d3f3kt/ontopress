<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once __DIR__.'/../../../wp-config.php';
$container = require __DIR__.'/src/OntoPress/bootstrap.php';

return ConsoleRunner::createHelperSet($container->get('doctrine'));
