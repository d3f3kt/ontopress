<?php

namespace OntoPress\Service;

use Doctrine\ORM\EntityManager;
use OntoPress\Entity\OntologyField;
use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\NamedNodeImpl;
use Saft\Rdf\NamedNode;
use Saft\Rdf\StatementImpl;
use Saft\Store\Store;

/**
 * Class ARC2Manager.
 *
 * ARC2Manager is the main service for storing resources as triples in a graph
 * It manages the creation of graphs and storing of a given resource
 */
class ARC2Manager
{
    /**
     * DoctrineManager Instance.
     *
     * @var EntityManager
     */
    private $entityManager;
    /**
     * ARC2 Instance to handle the saving and graph creation.
     *
     * @var Store
     */
    private $arc2;
    /**
     * NodeFactoryImpl Instance to create Nodes, that are stored in triples.
     *
     * @var NodeFactoryImpl
     */
    private $nodeFactory;

    const INT_URI = 'xsd:integer';
    const STRING_URI = 'xsd:string';
    const BOOL_URI = 'xsd:boolean';

    public function __construct(Store $arc2, EntityManager $entityManager)
    {
        $this->arc2 = $arc2;
        $this->entityManager = $entityManager;
        $this->nodeFactory = new NodeFactoryImpl();
    }

    /**
     * Main method to store the given information on a resource into
     * a graph, that represents the belonging ontology.
     *
     * @param array  $formData data of previously submitted form
     * @param String $author   name of the author
     *
     * @throws \Exception if no graph-name could be found
     */
    public function store($formData, $formId, $author = null)
    {
        $statements = $this->generateStatements($formData, $formId, $author);
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
     * the resource.
     *
     * @param array  $formData
     * @param int    $formId   ID of the form used to save this resource
     * @param String $author   name of the author
     *
     * @return array Array that contains all generated triples
     */
    private function generateStatements($formData, $formId, $author)
    {
        $statementArray = array();
        $subjectName = $this->getSubjectName($formData);
        foreach ($formData as $fieldIdText => $propertyValue) {
            $id = $this->makeId($fieldIdText);
            $ontoField = $this->getOntoField($id);
            if (!$ontoField) {
                $predicateUri = $this->createUriFromName('name', 'OntoPress');
                $statementArray[] = $this->generateTriple($subjectName, $predicateUri, $propertyValue);
            } else {
                $statementArray[] = $this->generateTriple($subjectName, $ontoField->getName(), $propertyValue);
            }
        }
        $predicateUriForm = $this->createUriFromName('formId', 'OntoPress');
        $statementArray[] = $this->generateTriple($subjectName, $predicateUriForm, $formId);

        $predicateUriAuthor = $this->createUriFromName('author', 'OntoPress');
        $statementArray[] = $this->generateTriple($subjectName, $predicateUriAuthor, $author);

        $predicateUriDate = $this->createUriFromName('date', 'OntoPress');
        $statementArray[] = $this->generateTriple($subjectName, $predicateUriDate, time());

        return $statementArray;
    }

    /**
     * Helper-method that extracts the id from given string.
     *
     * @param String $idText Name of a OntologyField, that contains its ID
     *
     * @return String extracted ID, or 'name' if no ID was found
     */
    private function makeId($idText)
    {
        return preg_replace("/OntologyField_(\d*)/", '$1', $idText);
    }

    /**
     * Helper-method to generate a triple from the resourcename, the name of
     * given property and its value.
     *
     * @param String $subjectName  name of the resource
     * @param String $predicateUri name of the property belonging to given value
     * @param $value
     *
     * @return StatementImpl The generated triple
     */
    private function generateTriple($subjectName, $predicateUri, $value)
    {
        $subjectUri = $this->createUriFromName($subjectName, 'OntoPress');

        $subject = new NamedNodeImpl($subjectUri);
        $predicate = new NamedNodeImpl($predicateUri);
        $object = $this->nodeFactory->createLiteral((string)$value, $this->getDatatypeNode($value));
        
        return new StatementImpl($subject, $predicate, $object);
    }

    /**
     * Helper-method to get the name of the resource.
     *
     * @param $formData
     *
     * @return String
     */
    private function getSubjectName($formData)
    {
        $title = $formData['OntologyField_'];
        if (strpbrk($title, 'äöüß!_')) {
            $translation = array("ä" => "ae", "ö" => "oe", "ü" => "ue", "ß" => "ss", "!" => "", "_" => "");
            $title = strtr($title, $translation);
        }
    // TODO: clean up title

        return $title;
    }

    /**
     * Helper-method that gets a name and a prefix and generates
     * a fictional URI.
     *
     * @param $name
     * @param $prefix
     *
     * @return string Generated URI
     */
    private function createUriFromName($name, $prefix = 'Undefined')
    {
        $trimName = str_replace(' ', '', $name);

        return $prefix.':'.$trimName;
    }

    /**
     * Helper-method to return an OntologyField by given ID.
     *
     * @param $id
     *
     * @return OntologyField
     */
    private function getOntoField($id)
    {
        return $this->entityManager->getRepository('OntoPress\Entity\OntologyField')
            ->findOneById($id);
    }

    /**
     * Helper-method to get the datatype of given value
     * and return it as a NamedNode to create a Literal
     *
     * @param mixed $objectData
     *
     * @return NamedNode Datatype of given value
     */
    private function getDatatypeNode($objectData)
    {
        //TODO eventually other types (e.g. for an ID)
        switch (gettype($objectData)) {
            case 'integer':
                return $this->nodeFactory->createNamedNode(ARC2Manager::INT_URI);
            case 'boolean':
                return $this->nodeFactory->createNamedNode(ARC2Manager::BOOL_URI);
            default:
                return $this->nodeFactory->createNamedNode(ARC2Manager::STRING_URI);
        }
    }

    public function suspendResource($resource)
    {
        $predicateUri = $this->createUriFromName('isSuspended', 'OntoPress');
        $setSuspended = $this->nodeFactory->createLiteral('true', $this->nodeFactory->createNamedNode(ARC2Manager::BOOL_URI));
        $this->arc2->addStatements(
            array(
                new StatementImpl(
                    new NamedNodeImpl($resource),
                    new NamedNodeImpl($predicateUri),
                    $setSuspended
        )));
    }
}
