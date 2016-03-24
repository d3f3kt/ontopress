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
        $parser = new ParserEasyRdf(
            new NodeFactoryImpl(),
            new StatementFactoryImpl(),
            'turtle' // RDF format of the file to parse later on (ttl => turtle)
        );
        $statementIterator = $parser->parseStreamToIterator($filepath);
        $objectArray = array();
        $restrictionArray = array();
        foreach ($statementIterator as $key => $statement) {
            if (!(array_key_exists($statement->getSubject()->getUri(), $objectArray))) {
                $objectArray[$statement->getSubject()->getUri()] = new OntologyNode($statement->getSubject()->getUri());
            }
            if ($statement->getPredicate() == "http://www.w3.org/2000/01/rdf-schema#label") {
                $objectArray[$statement->getSubject()->getUri()]->setLabel($statement->getObject()->getValue());
                $objectArray[$statement->getSubject()->getUri()]->setType(OntologyNode::TYPE_TEXT);
            } elseif ($statement->getPredicate() == "http://localhost/k00ni/knorke/restrictionOneOf") {
                if ($restrictionArray[$statement->getSubject()->getUri()] == null) {
                    $restrictionArray[$statement->getSubject()->getUri()] = new Restriction();
                    $restrictionArray[$statement->getSubject()->getUri()]->addOneOf($statement->getObject()->getUri());
                } else {
                    $restrictionArray[$statement->getSubject()->getUri()]->addOneOf($statement->getObject()->getUri());
                }
                $objectArray[$statement->getSubject()->getUri()]->setType(OntologyNode::TYPE_RADIO);
            }
            if ($statement->getPredicate() == "http://www.w3.org/2000/01/rdf-schema#comment") {
                $objectArray[$statement->getSubject()->getUri()]->setComment($statement->getObject()->getValue());
            }
            if ($statement->getPredicate() == "http://localhost/k00ni/knorke/isMandatory") {
                if ($statement->getObject()->getValue()) {
                    $objectArray[$statement->getSubject()->getUri()]->setMandatory(true);
                } else {
                    $objectArray[$statement->getSubject()->getUri()]->setMandatory(false);
                }
            }
        }
        foreach ($restrictionArray as $subject => $restriction) {
            $restrictionObject = new Restriction();
            foreach ($restriction->getOneOf() as $key => $choice) {
                if ($objectArray[$choice] != null) {
                    $objectArray[$choice]->setType(OntologyNode::TYPE_CHOICE);
                }
                $restrictionObject->addOneOf($objectArray[$choice]);
            }
            $objectArray[$subject]->setRestriction($restrictionObject);
        }
        
        return $objectArray;
    }
}
