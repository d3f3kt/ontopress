<?php
namespace OntoPress\Service\OntologyParser;

use OntoPress\Entity\Ontology;
use Saft\Addition\EasyRdf\Data\ParserEasyRdf;
use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\StatementFactoryImpl;
use OntoPress\Service\OntologyParser\OntologyNode;
use OntoPress\Service\OntologyParser\Restriction;
use OntoPress\Entity\OntologyField;
use OntoPress\Entity\Restriction as RestrictionEntity;

/**
 * Class Parser
 * @package OntoPress\Service\OntologyParser
 */
class Parser
{
    /**
     * Parsing-method, to parse an Ontology-object to OntologyNode
     * @param Ontology
     * @param boolean
     * @return array Array of OntologyNodes
     * @return boolean
     * @throws \Exception
     */
    public function parsing($ontology, $writeData = false)
    {
        $parser = new ParserEasyRdf(
            new NodeFactoryImpl(),
            new StatementFactoryImpl(),
            'turtle' // RDF format of the file to parse later on (ttl => turtle)
        );
        
        $ontologyArray = $ontology->getOntologyFiles();
        $objectArray = array();
        $restrictionArray = array();
        
        foreach ($ontologyArray as $index => $ontologyFile) {
            $statementIterator = $parser->parseStreamToIterator($ontologyFile->getAbsolutePath());
            
            foreach ($statementIterator as $key => $statement) {
                if (!(array_key_exists($statement->getSubject()->getUri(), $objectArray))) {
                    $objectArray[$statement->getSubject()->getUri()] = new OntologyNode($statement->getSubject()->getUri(), null, null, OntologyNode::TYPE_TEXT);
                }
                if ($statement->getPredicate() == "http://www.w3.org/2000/01/rdf-schema#label") {
                    $objectArray[$statement->getSubject()->getUri()]->setLabel($statement->getObject()->getValue());
                }
                if ($statement->getPredicate() == "http://localhost/k00ni/knorke/restrictionOneOf") {
                    $restrictionArray = $this->restrictionHandler($statement, $restrictionArray);
                    $objectArray[$statement->getSubject()->getUri()]->setType(OntologyNode::TYPE_RADIO);
                }
                if ($statement->getPredicate() == "http://www.w3.org/2000/01/rdf-schema#comment") {
                    $objectArray[$statement->getSubject()->getUri()]->setComment($statement->getObject()->getValue());
                }
                if ($statement->getPredicate() == "http://localhost/k00ni/knorke/isMandatory") {
                    $objectArray[$statement->getSubject()->getUri()]->setMandatory(true);
                }
            }
        }

        foreach ($restrictionArray as $subject => $restriction) {
            $restrictionObject = new Restriction();
            foreach ($restriction->getOneOf() as $key => $choice) {
                if (isset($objectArray[$choice])) {
                    $objectArray[$choice]->setType(OntologyNode::TYPE_CHOICE);
                    $restrictionObject->addOneOf($objectArray[$choice]->getName());
                } else {
                    $restrictionObject->addOneOf($choice);
                }
            }
            $objectArray[$subject]->setRestriction($restrictionObject);
        }

        if ($writeData) {
            foreach ($objectArray as $key => $object) {
                $newNode = new OntologyField();
                $newNode->setName($object->getName());
                $newNode->setLabel($object->getLabel());
                $newNode->setComment($object->getComment());
                $newNode->setType($object->getType());
                $newNode->setMandatory($object->getMandatory());

                if (!empty($object->getRestriction())) {
                    foreach ($object->getRestriction()->getOneOf() as $resKey => $resObject) {
                        $newRestriction = new RestrictionEntity();
                        $newNode->addRestriction($newRestriction->setName($resObject));
                    }
                }

                $dataOntologyArray = $ontology->getDataOntologies();
                $newDataOntology = true;

                foreach($dataOntologyArray as $arrayKey => $dataOntology) {
                    if($dataOntology->getName() == $this->parseNodeName($object->getName())) {
                        // speichere Node in diese dataOntology
                        $newDataOntology = false;
                    }
                }
                if($newDataOntology) {
                    // neue DataOntology erstellen und da rein speichern
                    // neue DataOntology muss parseNodeName() als namen kriegen
                }
                // (altes Ende bzw. schreiben in Datenbank:)
                // $ontology->addOntologyField($newNode);
            }
            return true;
        }

        return $objectArray;
    }

    public function restrictionHandler($statement, $restrictionArray)
    {
        if (!(isset($restrictionArray[$statement->getSubject()->getUri()]))) {
            $restrictionArray[$statement->getSubject()->getUri()] = new Restriction();
            $restrictionArray[$statement->getSubject()->getUri()]->addOneOf($statement->getObject()->getUri());
        } else {
            $restrictionArray[$statement->getSubject()->getUri()]->addOneOf($statement->getObject()->getUri());
        }
        return $restrictionArray;
    }

    public function parseNodeName($ontologyNodeName)
    {
        $parsedName = $ontologyNodeName;
        // schau den namen an, und suche String der immer gleich ausfallen würde
        return $parsedName;
    }
}
