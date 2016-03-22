<?php

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('Resources')
    ->exclude('Tests')
    ->in('src/OntoPress');

$sami = new Sami($iterator, array(
    'title' => 'OntoPress API',
    'build_dir' => __DIR__.'/doc/api',
    'cache_dir' => __DIR__.'/cache',
));

return $sami;
