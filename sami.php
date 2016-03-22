<?php

use Sami\Sami;
use Sami\Parser\Filter\TrueFilter;

$sami = new Sami('src/OntoPress/');
// document all methods and properties
$sami['filter'] = function () {
    return new TrueFilter();
};

return $sami;
