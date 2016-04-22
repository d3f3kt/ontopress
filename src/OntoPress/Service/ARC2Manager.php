<?php

namespace OntoPress\Service;

use Saft\Addition\ARC2\Store\ARC2;

class ARC2Manager
{

    private $arc2;

    public function __construct(ARC2 $arc2)
    {
        $this->arc2 = $arc2;
    }
}
