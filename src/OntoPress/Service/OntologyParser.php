<?php

namespace OntoPress\Service;

use OntoPress\Entity\DataOntology;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyField;
use OntoPress\Entity\Restriction;
use Saft\Addition\EasyRdf\Data\ParserEasyRdf as Parser;

/**
 * Class OntologyParser
 *
 * The OntologyParser parses a given Ontology-Object to OntologyNodes.
 */
class OntologyParser
{
    /**
     * A EasyRDFParser instance
     *
     * @var Parser
     */
    private $parser;

    /**
     * The Constructor is automatically called by creating a new OntologyParser.
     * It initializes the parser instance with the given parameter.
     *
     * @param $parser Parser EasyRDFParser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }
    /**
     * Parsing-method, to parse an Ontology-object to OntologyFields.
     *
     * @param Ontology
     *
     * @return array Array of OntologyFields
     *
     * @throws \Exception
     */
    public function parsing($ontology)
    {
        $ontologyArray = $ontology->getOntologyFiles();
        $objectArray = array();
        foreach ($ontologyArray as $index => $ontologyFile) {
            $statementIterator = $this->parser->parseStreamToIterator($ontologyFile->getAbsolutePath());
            foreach ($statementIterator as $key => $statement) {
                $objectArray = $this->initElement($statement, $objectArray);
                switch ($statement->getPredicate()) {
                    case 'http://www.w3.org/2000/01/rdf-schema#label':
                        $objectArray[$statement->getSubject()->getUri()]->setLabel($statement->getObject()->getValue());
                        break;
                    case 'http://inspirito.de/ontology/knorke/ns#restrictionOneOf':
                        $objectArray[$statement->getSubject()->getUri()] = $this->restrictionHandler($statement, $objectArray[$statement->getSubject()->getUri()]);
                        break;
                    case 'http://www.w3.org/2000/01/rdf-schema#comment':
                        $objectArray[$statement->getSubject()->getUri()]->setComment($statement->getObject()->getValue());
                        break;
                    case 'http://inspirito.de/ontology/knorke/ns#isMandatory':
                        $objectArray[$statement->getSubject()->getUri()]->setMandatory(true);
                        break;
                    case 'http://inspirito.de/ontology/knorke/ns#restrictionRegex':
                        $objectArray[$statement->getSubject()->getUri()]->setRegex($statement->getObject()->getValue());
                        break;
                }
            }
            foreach ($statementIterator as $key => $statement) {
                if ($statement->getPredicate() == 'http://inspirito.de/ontology/knorke/ns#restrictionOneOf' && isset($objectArray[$statement->getObject()->getUri()])) {
                    $objectArray[$statement->getObject()->getUri()]->setType(OntologyField::TYPE_CHOICE);
                }
            }
        }
        $objectArray = $this->propertyHandler($statementIterator, $objectArray);
        $objectArray = $this->selectHandler($objectArray);
        $this->dataOntologyHandler($ontology, $objectArray);
        return $objectArray;
    }

    /**
     * A parsing-helper function, which handles the Restrictions
     *
     * @param $statement
     * @param $ontologyField
     * @return array
     */
    public function restrictionHandler($statement, $ontologyField)
    {
        $ontologyField->setType(OntologyField::TYPE_RADIO);
        $restriction = new Restriction();
        $restriction->setName($statement->getObject()->getUri());
        $ontologyField->addRestriction($restriction);
        return $ontologyField;
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
            switch ($statement->getPredicate()) {
                case 'http://inspirito.de/ontology/knorke/ns#hasProperty':
                    if (!(array_key_exists($statement->getObject()->getUri(), $objectArray))) {
                        $objectArray[$statement->getObject()->getUri()] = new OntologyField();
                        $objectArray[$statement->getObject()->getUri()]->setName($statement->getObject()->getUri());
                        $objectArray[$statement->getObject()->getUri()]->setType(OntologyField::TYPE_TEXT);
                    }
                    $objectArray[$statement->getObject()->getUri()]->setPossessed(true);
                    break;
                case 'http://www.w3.org/1999/02/22-rdf-syntax-ns#type':
                    if (($statement->getObject() == 'http://inspirito.de/ontology/knorke/ns#RestrictionElement') ||
                        $statement->getObject() == 'http://inspirito.de/ontology/knorke/ns#Property'
                    ) {
                        $objectArray[$statement->getSubject()->getUri()]->setPossessed(true);
                    }
                    break;
            }
        }

        return $objectArray;
    }

    /**
     * A parsing-helper function
     *
     * @param Ontology $ontology
     * @param array $objectArray
     * @return bool
     */
    public function dataOntologyHandler($ontology, $objectArray)
    {
        foreach ($objectArray as $key => $object) {
            $dataOntologyArray = $ontology->getDataOntologies();
            $newDataOntology = true;

            foreach ($dataOntologyArray as $arrayKey => $dataOntology) {
                if ($dataOntology->getName() == $this->groupOntologies($object->getName())) {
                    $dataOntology->addOntologyField($object);
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

    /**
     * Initiate objects if not already part of the objectArray
     *
     * @param $statement
     * @param $objectArray
     * @return mixed
     */
    public function initElement($statement, $objectArray)
    {
        if (!(array_key_exists($statement->getSubject()->getUri(), $objectArray))) {
            $objectArray[$statement->getSubject()->getUri()] = new OntologyField();
            $objectArray[$statement->getSubject()->getUri()]->setName($statement->getSubject()->getUri());
            $objectArray[$statement->getSubject()->getUri()]->setType(OntologyField::TYPE_TEXT);
        }
        return $objectArray;
    }

    /**
     * Sets TYPE_SELECT in properties with 3 or more choices
     *
     * @param array() $objectArray
     * @return array() OntologyField array
     */
    public function selectHandler($objectArray)
    {
        foreach ($objectArray as $key => $object) {
            if (sizeof($object->getRestrictions()) >= 3) {
                $object->setType(OntologyField::TYPE_SELECT);
            }
        }
        return $objectArray;
    }
}
