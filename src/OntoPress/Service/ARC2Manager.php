<?php

namespace OntoPress\Service;

use Doctrine\ORM\EntityManager;
use Mockery\CountValidator\Exception;
use OntoPress\Entity\OntologyField;
use Saft\Addition\ARC2\Store\ARC2;
use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\NamedNodeImpl;
use Saft\Rdf\StatementImpl;

class ARC2Manager
{
    private $entityManager;
    private $arc2;
    private $nodeFactory;

    private $xsdInt;
    private $xsdString;

    public function __construct(ARC2 $arc2, EntityManager $entityManager)
    {
        $this->arc2 = $arc2;
        $this->entityManager = $entityManager;
        $this->nodeFactory = new NodeFactoryImpl();

        $this->xsdInt = $this->nodeFactory->createNamedNode('http://www.w3.org/2001/XMLSchema#integer');
    }

    /**
     * @param $formData
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
     * @param $formData
     * @return array
     */
    private function generateStatements($formData)
    {
        $statementArray = array();
        $subjectUri = $this->getSubjectName($formData);
        foreach ($formData as $fieldIdText => $propertyValue) {
            $id = $this->makeId($fieldIdText);
            $ontoField = $this->getOntoField($id);
            /*$this->entityManager->getRepository('OntoPress\Entity\OntologyField')
                ->findOneById($id); */
            if (!$ontoField) {
                $predicateUri = $this->createUriFromName($id, 'FieldId');
                $statementArray[] = $this->generateTriple($subjectUri, $predicateUri, $propertyValue);
            } else {
                $statementArray[] = $this->generateTriple($subjectUri, $ontoField->getName(), $propertyValue);
            }
        }
        return $statementArray;
    }

    /**
     * @param $idText
     * @return string
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
     * @param $subjectUri
     * @param $ontoField
     * @param $value
     * @return StatementImpl
     */
    private function generateTriple($subjectName, $predicateUri, $value)
    {
        $subjectUri = $this->createUriFromName($subjectName);

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
     * @param $formData
     * @return mixed
     */
    private function getSubjectName($formData)
    {
        $title = $formData['OntologyField_'];

        // TODO: clean up title

        return $title;
    }

    /**
     * @param $name
     * @param $prefix
     * @return string
     */
    private function createUriFromName($name, $prefix = 'name:')
    {
        return $prefix . ':' . $name;
    }

    private function getOntoField($id)
    {
        return $this->entityManager->getRepository('OntoPress\Entity\OntologyField')
            ->findOneById($id);
    }
}
