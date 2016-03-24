<?php

namespace OntoPress\Controller;

use OntoPress\Libary\AbstractController;

class TestController extends AbstractController
{
    public function showAddAction()
    {
        $ontologyParser = $this->get('ontopress.ontology_parser');
        $output = $ontologyParser->parsing(__DIR__.'/../Resources/ontology/place-ontology.ttl');
        
        foreach ($output as $key => $object) {
            echo $object->getName() . " - " . $object->getLabel() . " - " .
                $object->getComment() . " - " . $object->getType() . " - " .
                $object->getMandatory() . " - ";
            if ($object->getRestriction() != null) {
                print_r($object->getRestriction());
                echo "<br />";
            }
            echo "<br />";
        }
        return null;
    }
}
