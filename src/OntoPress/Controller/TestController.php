<?php

namespace OntoPress\Controller;

use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyFile;
use OntoPress\Library\AbstractController;

class TestController extends AbstractController
{
    public function showAddAction()
    {
        $ontologyFile = new OntologyFile();
        $ontologyFile->setPath('/../../../Tests/TestFiles/place-ontology.ttl');
        
        $ontologyObj = new Ontology();
        $ontologyObj->setName("Place")
            ->addOntologyFile($ontologyFile);
        
        $ontologyParser = $this->get('ontopress.ontology_parser');
        $output = $ontologyParser->parsing($ontologyObj);
        
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
