<?php

namespace OntoPress\Service;

use OntoPress\Entity\DataOntology;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyField;
use OntoPress\Entity\Restriction;
use Saft\Addition\EasyRdf\Data\ParserEasyRdf as Parser;

/**
 * Class Parser.
 */
class OntologyParser
{
    private $parser;
    
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }
    /**
     * Parsing-method, to parse an Ontology-object to OntologyNode.
     *
     * @param Ontology
     *
     * @return array Array of OntologyNodes
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
                /*
                if (!(array_key_exists($statement->getSubject()->getUri(), $objectArray))) {
                $objectArray[$statement->getSubject()->getUri()] = new OntologyField();
                $objectArray[$statement->getSubject()->getUri()]->setName($statement->getSubject()->getUri());
                $objectArray[$statement->getSubject()->getUri()]->setType(OntologyField::TYPE_TEXT);
                }
                */
                $objectArray = $this->initElement($statement, $objectArray);
                switch ($statement->getPredicate()) {
                    case 'http://www.w3.org/2000/01/rdf-schema#label':
                        $objectArray[$statement->getSubject()->getUri()]->setLabel($statement->getObject()->getValue());
                        break;
                    case 'http://localhost/k00ni/knorke/restrictionOneOf':
                        $objectArray[$statement->getSubject()->getUri()] = $this->restrictionHandler($statement, $objectArray[$statement->getSubject()->getUri()]);
                        break;
                    case 'http://www.w3.org/2000/01/rdf-schema#comment':
                        $objectArray[$statement->getSubject()->getUri()]->setComment($statement->getObject()->getValue());
                        break;
                    case 'http://localhost/k00ni/knorke/isMandatory':
                        $objectArray[$statement->getSubject()->getUri()]->setMandatory(true);
                        break;
                }
            }
            foreach ($statementIterator as $key => $statement) {
                if ($statement->getPredicate() == 'http://localhost/k00ni/knorke/restrictionOneOf' && isset($objectArray[$statement->getObject()->getUri()])) {
                    $objectArray[$statement->getObject()->getUri()]->setType(OntologyField::TYPE_CHOICE);
                }
            }
        }
        $objectArray = $this->propertyHandler($statementIterator, $objectArray);
        $this->dataOntologyHandler($ontology, $objectArray);
        return $objectArray;
    }

    /**
     * A parsing-helper function, which handles the Restrictions
     *
     * @param $statement
     * @param $ontologyField
     * @return array
     *
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
            if ($statement->getPredicate() == 'http://localhost/k00ni/knorke/hasProperty') {
                if (!(array_key_exists($statement->getObject()->getUri(), $objectArray))) {
                    $objectArray[$statement->getObject()->getUri()] = new OntologyField();
                    $objectArray[$statement->getObject()->getUri()]->setName($statement->getObject()->getUri());
                    $objectArray[$statement->getObject()->getUri()]->setType(OntologyField::TYPE_TEXT);
                }
                $objectArray[$statement->getObject()->getUri()]->setPossessed(true);
            }
            switch ($statement->getPredicate()) {
                case 'http://localhost/k00ni/knorke/hasProperty':
                    if (!(array_key_exists($statement->getObject()->getUri(), $objectArray))) {
                        $objectArray[$statement->getObject()->getUri()] = new OntologyField();
                        $objectArray[$statement->getObject()->getUri()]->setName($statement->getObject()->getUri());
                        $objectArray[$statement->getObject()->getUri()]->setType(OntologyField::TYPE_TEXT);
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
}
