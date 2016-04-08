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
        $ontologyParser->parsing($ontologyObj, true);

        $output = $ontologyParser->parsing($ontologyObj);
        foreach ($output as $key => $object) {
            echo $object->getName() . " - " . $object->getLabel() . " - " .
                $object->getComment() . " - " . $object->getType() . " - " .
                $object->getPossessed() . " - " .$object->getMandatory() . " - ";
            foreach ($object->getRestrictions() as $key2 => $restriction){
                echo "<br />" . $restriction->getName();
            }
            echo "<br /><br />";

        }

        return null;
    }
}
