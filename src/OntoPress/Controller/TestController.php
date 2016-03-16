<?php

namespace OntoPress\Controller;

use OntoPress\Libary\AbstractController;
use OntoPress\Service\OntologyParser;

class TestController extends AbstractController
{
    public function showAddAction()
    {
        $ontologyParser = $this->get('ontopress.ontology_parser');
        $ontologyParser->parsing(__DIR__.'/../Resources/ontology/place-ontology.ttl');
        return null;
    }
}
