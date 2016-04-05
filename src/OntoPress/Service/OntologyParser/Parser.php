<?php

namespace OntoPress\Service\OntologyParser;

use OntoPress\Entity\DataOntology;
use OntoPress\Entity\Ontology;
use Saft\Addition\EasyRdf\Data\ParserEasyRdf;
use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\StatementFactoryImpl;
use OntoPress\Entity\OntologyField;
use OntoPress\Entity\Restriction as RestrictionEntity;

/**
 * Class Parser.
 */
class Parser
{
    /**
     * Parsing-method, to parse an Ontology-object to OntologyNode.
     *
     * @param Ontology
     * @param bool
     *
     * @return array Array of OntologyNodes
     *
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
                switch ($statement->getPredicate()) {
                    case 'http://www.w3.org/2000/01/rdf-schema#label':
                        $objectArray[$statement->getSubject()->getUri()]->setLabel($statement->getObject()->getValue());
                        break;
                    case 'http://localhost/k00ni/knorke/restrictionOneOf':
                        $restrictionArray = $this->restrictionHandler($statement, $restrictionArray);
                        $objectArray[$statement->getSubject()->getUri()]->setType(OntologyNode::TYPE_RADIO);
                        break;
                    case 'http://www.w3.org/2000/01/rdf-schema#comment':
                        $objectArray[$statement->getSubject()->getUri()]->setComment($statement->getObject()->getValue());
                        break;
                    case 'http://localhost/k00ni/knorke/isMandatory':
                        $objectArray[$statement->getSubject()->getUri()]->setMandatory(true);
                        break;
                }
            }
        }
        $objectArray = $this->propertyHandler($statementIterator, $objectArray);

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
            $this->writeDataHandler($ontology, $objectArray);
        }

        return $objectArray;
    }

    /**
     * A parsing-helper function, which handles the Restrictions
     *
     * @param $statement
     * @param array $restrictionArray An array with Restrictions
     * @return array
     *
     */
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

    /**
     * A parsing-helper function which handles the Property, hasProperty and the RestrictionElement Ontology relations
     *
     * @param $statementIterator
     * @param array $objectArray
     * @return array $objectArray
     */
    public function propertyHandler($statementIterator, $objectArray)
    {
        foreach ($statementIterator as $key => $statement) {
            if ($statement->getPredicate() == 'http://localhost/k00ni/knorke/hasProperty') {
                if (!(array_key_exists($statement->getObject()->getUri(), $objectArray))) {
                    $objectArray[$statement->getObject()->getUri()] = new OntologyNode($statement->getObject()->getUri(), null, null, OntologyNode::TYPE_TEXT);
                }
                $objectArray[$statement->getObject()->getUri()]->setPossessed(true);
            }
            switch ($statement->getPredicate()) {
                case 'http://localhost/k00ni/knorke/hasProperty':
                    if (!(array_key_exists($statement->getObject()->getUri(), $objectArray))) {
                        $objectArray[$statement->getObject()->getUri()] = new OntologyNode($statement->getObject()->getUri(), null, null, OntologyNode::TYPE_TEXT);
                    }
                    $objectArray[$statement->getObject()->getUri()]->setPossessed(true);
                    break;
                case 'http://www.w3.org/1999/02/22-rdf-syntax-ns#type':
                    if (($statement->getObject() == 'http://localhost/k00ni/knorke/RestrictionElement') ||
                        $statement->getObject() == 'http://localhost/k00ni/knorke/Property'
                    ) {
                        $objectArray[$statement->getSubject()->getUri()]->setPossessed(true);
                    }
                    break;
            }
        }
        foreach ($statementIterator as $key => $statement) {
            if (isset($objectArray[$statement->getSubject()->getUri()]) &&
                (!($objectArray[$statement->getSubject()->getUri()]->getPossessed()))
            ) {
                unset($objectArray[$statement->getSubject()->getUri()]);
            }
        }

        return $objectArray;
    }

    /**
     * A parsing-helper function which handles the database interaction to save every property and restriction
     *
     * @param Ontology $ontology
     * @param array $objectArray
     * @return bool
     */
    public function writeDataHandler($ontology, $objectArray)
    {
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

            foreach ($dataOntologyArray as $arrayKey => $dataOntology) {
                if ($dataOntology->getName() == $this->groupOntologies($object->getName())) {
                    $dataOntology->addOntologyField($newNode);
                    $newDataOntology = false;
                }
            }
            if ($newDataOntology) {
                $newData = new DataOntology();
                $newData->setName($this->groupOntologies($object->getName()));
                $ontology->addDataOntology($newData);
            }
        }

        return true;
    }

    /**
     * Get last part of an Uri.
     *
     * @param string $uri Uri e.g. of an ontology node
     *
     * @return string last part of the Uri
     */
    public function getUriFile($uri)
    {
        $parts = explode('/', $uri);
        $revParts = array_reverse($parts);
        return $revParts[0];
    }

    /**
     * Cut the last part of an Uri
     *
     * @param string $ontologyNodeName Uri e.g. of an ontology node
     * @return string The Uri without the last part
     */
    public function groupOntologies($ontologyNodeName)
    {
        $pos = strrpos($ontologyNodeName, "/");
        $pos = (strlen($ontologyNodeName) - $pos) * -1;
        $parsedName = substr($ontologyNodeName, 0, $pos);
        return $parsedName;
    }
}
