<?php
namespace OntoPress\Service\OntologyParser;

use Saft\Addition\EasyRdf\Data\ParserEasyRdf;
use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\StatementFactoryImpl;
use OntoPress\Service\OntologyParser\OntologyNode;
use OntoPress\Service\OntologyParser\Restriction;

class Parser
{
    public function parsing($filepath)
    {
        // $fileContent1 = file_get_contents(__DIR__.'/../Resources/ontology/knorke.ttl');
        // $fileContent = file_get_contents(__DIR__.'/../Resources/ontology/place-ontology.ttl');
        //$fileContent = $fileContent1 . $fileContent2;
        $parser = new ParserEasyRdf(
            new NodeFactoryImpl(),
            new StatementFactoryImpl(),
            'turtle' // RDF format of the file to parse later on (ttl => turtle)
        );
        $statementIterator = $parser->parseStreamToIterator($filepath);
        /*
        foreach ($statementIterator as $key => $statement) {
            echo '#' . $key . ' - ' .
                (string)$statement->getSubject() . ' - ' .
                (string)$statement->getPredicate() . ' - ' .
                (string)$statement->getObject();
            echo "<br />";
        }
           */
        $objectArray = array();
        $restrictionArray = array();
        foreach ($statementIterator as $key => $statement) {
            if (!(array_key_exists($statement->getSubject()->getUri(), $objectArray))) {
                $objectArray[$statement->getSubject()->getUri()] = new OntologyNode($statement->getSubject()->getUri(), null, null, null, null, null);
            }
            if ($statement->getPredicate() == "http://www.w3.org/2000/01/rdf-schema#label") {
                $objectArray[$statement->getSubject()->getUri()]->setLabel($statement->getObject()->getValue());
                $objectArray[$statement->getSubject()->getUri()]->setType(TYPE_TEXT);
            } elseif ($statement->getPredicate() == "http://localhost/k00ni/knorke/restrictionOneOf") {
                if ($objectArray[$statement->getSubject()->getUri()]->getRestriction() == null) {
                    $objectArray[$statement->getSubject()->getUri()]->setRestriction(new Restriction($statement->getObject()->getUri()));
                } else {
                    $objectArray[$statement->getSubject()->getUri()]->getRestriction()->addOneOf($statement->getObject()->getUri());
                }
                $objectArray[$statement->getSubject()->getUri()]->setType(TYPE_BUTTON);
                /*
                if ($restrictionArray[$statement->getSubject()->getUri()] == null) {
                    $restrictionArray[($statement->getSubject()->getUri()] = array($statement->getObject()->getUri());
                } else {
                    array_push($restrictionArray[$statement->getSubject()->getUri()], $statement->getObject()->getUri());
                }
                */
            }
            if ($statement->getPredicate() == "http://www.w3.org/2000/01/rdf-schema#comment") {
                $objectArray[$statement->getSubject()->getUri()]->setComment($statement->getObject()->getValue());
            }
            if ($statement->getPredicate() == "http://localhost/k00ni/knorke/isMandatory") {
                if ($statement->getObject()->getValue() == "true") {
                    $objectArray[$statement->getSubject()->getUri()]->setMandatory(true);
                } else {
                    $objectArray[$statement->getSubject()->getUri()]->setMandatory(false);
                }
            }
        }
        foreach ($objectArray as $key => $object) {
            echo $object->getName() . " - " . $object->getLabel() . " - " .
                $object->getComment() . " - " . $object->getType() . " - " .
                $object->getMandatory() . " - ";
            if ($object->getRestriction() != null) {
                print_r($object->getRestriction());
            }
            echo "<br />";
        }
        return $objectArray;
    }
}
