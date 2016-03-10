<?php

namespace OntoPress\Controller;

use OntoPress\Libary\AbstractController;
use OntoPress\Service\OntologyParser;

class TestController extends AbstractController
{
    public function showAddAction()
    {
        $testParser = new OntologyParser();
        echo "Hallo";
        return null;
    }
}
