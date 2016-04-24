<?php

namespace OntoPress\Service;

use Doctrine\ORM\EntityManager;
use Mockery\CountValidator\Exception;
use OntoPress\Entity\OntologyField;
use Saft\Addition\ARC2\Store\ARC2;
use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\NamedNodeImpl;
use Saft\Rdf\StatementImpl;

/**
 * Class ARC2Manager
 *
 * ARC2Manager is the main service for storing resources as triples in a graph
 * It manages the creation of graphs and storing of a given resource
 */
class ARC2Manager
{
    /**
     * DoctrineManager Instance
     *
     * @var EntityManager
     */
    private $entityManager;
    /**
     * ARC2 Instance to handle the saving and graph creation
     *
     * @var ARC2
     */
    private $arc2;
    /**
     * NodeFactoryImpl Instance to create Nodes, that are stored in triples
     *
     * @var NodeFactoryImpl
     */
    private $nodeFactory;

    public function __construct(ARC2 $arc2, EntityManager $entityManager)
    {
        $this->arc2 = $arc2;
        $this->entityManager = $entityManager;
        $this->nodeFactory = new NodeFactoryImpl();
    }

    /**
     * Main method to store the given information on a resource into
     * a graph, that represents the belonging ontology
     *
     * @param array $formData
     * @throws \Exception
     */
    public function store($formData)
    {
        $statements = $this->generateStatements($formData);
        $saveGraphName = null;
        foreach ($formData as $key => $obj) {
            $field = $this->getOntoField($this->makeId($key));
            if ($field !== null) {
                $saveGraphName = $field->getDataOntology()->getOntology()->getName();
            }
        }
        if (!$saveGraphName) {
            throw new \Exception('Form corrupted, no ontology found');
        } else {
            $saveGraphName = $this->createUriFromName($this->makeId($saveGraphName), 'graph');
        }
        $graphs = $this->arc2->getGraphs();
        if (!array_key_exists($saveGraphName, $graphs)) {
            $this->arc2->createGraph(new NamedNodeImpl($saveGraphName));
        }
        $this->arc2->addStatements($statements, $graphs[$saveGraphName]);
    }

    /**
     * Helper-method that generates an array of triples from the given information on
     * the resource
     *
     * @param array $formData
     * @return array Array that contains all generated triples
     */
    private function generateStatements($formData)
    {
        $statementArray = array();
        $subjectName = $this->getSubjectName($formData);
        foreach ($formData as $fieldIdText => $propertyValue) {
            $id = $this->makeId($fieldIdText);
            $ontoField = $this->getOntoField($id);
            if (!$ontoField) {
                $predicateUri = $this->createUriFromName($id, 'FieldId');
                $statementArray[] = $this->generateTriple($subjectName, $predicateUri, $propertyValue);
            } else {
                $statementArray[] = $this->generateTriple($subjectName, $ontoField->getName(), $propertyValue);
            }
        }
        /*
        $predicateUri = $this->createUriFromName('author', 'OntoPress');
        $statementArray[] = $this->generateTriple($subjectName, $predicateUri, AUTHOR);
        $predicateUri = $this->createUriFromName('date', 'OntoPress');
        $statementArray[] = $this->generateTriple($subjectName, $predicateUri, DATE);
        */
        return $statementArray;
    }

    /**
     * Helper-method that extracts the id from given string
     *
     * @param String $idText Name of a OntologyField, that contains its ID
     * @return String extracted ID, or 'name' if no ID was found
     */
    private function makeId($idText)
    {
        if ($id = preg_replace("/OntologyField_(\d*)/", "$1", $idText)) {
            return $id;
        } else {
            return 'name';
        }
    }

    /**
     * Helper-method to generate a triple from the resourcename, the name of
     * given property and its value
     *
     * @param String $subjectName name of the resource
     * @param String $predicateUri name of the property belonging to given value
     * @param $value
     * @return StatementImpl The generated triple
     */
    private function generateTriple($subjectName, $predicateUri, $value)
    {
        $subjectUri = $this->createUriFromName($subjectName, 'OntoPress');

        $subject = new NamedNodeImpl($subjectUri);
        $predicate = new NamedNodeImpl($predicateUri);
        $object = $this->nodeFactory->createLiteral((string) $value);
        /*
        switch (gettype($value)) {
            case 'string':
                $object = $this->nodeFactory->createLiteral($value);
                break;
            case 'integer':
                $object = $this->nodeFactory->createLiteral((string) $value);
                break;
            default:  
        }
        */
        return new StatementImpl($subject, $predicate, $object);
    }

    /**
     * Helper-method to get the name of the resource
     *
     * @param $formData
     * @return String
     */
    private function getSubjectName($formData)
    {
        $title = $formData['OntologyField_'];

        // TODO: clean up title

        return $title;
    }

    /**
     * Helper-method that gets a name and a prefix and generates
     * a fictional URI
     *
     * @param $name
     * @param $prefix
     * @return string Generated URI
     */
    private function createUriFromName($name, $prefix = 'Undefined')
    {
        $trimName = str_replace(' ', '', $name);
        return $prefix . ':' . $trimName;
    }

    /**
     * Helper-method to return an OntologyField by given ID
     *
     * @param $id
     * @return OntologyField
     */
    private function getOntoField($id)
    {
        return $this->entityManager->getRepository('OntoPress\Entity\OntologyField')
            ->findOneById($id);
    }
}
