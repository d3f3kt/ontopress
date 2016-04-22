<?php

namespace OntoPress\Service;

use Doctrine\ORM\EntityManager;
use Saft\Addition\ARC2\Store\ARC2;
use Saft\Addition\Redland\Rdf\NamedNode;
use Saft\Rdf\NamedNodeImpl;
use Saft\Rdf\StatementImpl;

class ARC2Manager
{
    private $entityManager;
    private $arc2;

    public function __construct(ARC2 $arc2, EntityManager $entityManager)
    {
        $this->arc2 = $arc2;
        $this->entityManager = $entityManager;
    }

    /**
     * @param $formData
     * @throws \Exception
     */
    public function store($formData)
    {
        $statements = $this->generateStatements($formData);
        $saveGraphName = $statements[0]->getDataOntology()->getOntology()->getName();
        $graphs = $this->arc2->getGraphs();

        if (!array_key_exists($saveGraphName, $graphs)) {
            $this->arc2->createGraph(new NamedNodeImpl($saveGraphName));
        }
        dump($graphs);
        $this->arc2->addStatements($statements, $graphs[$saveGraphName]);
    }

    /**
     * @param $formData
     * @return array
     */
    private function generateStatements($formData)
    {
        $statementArray = array();
        $subjectUri = $this->getSubjectUri($formData);
        foreach ($formData as $fieldIdText => $propertyValue) {
            $id = $this->makeId($fieldIdText);
            $ontoField = $this->entityManager->getRepository('OntoPress\Entity\OntologyField')
                ->findOneById($id);
            $statementArray[] = $this->generateTriple($subjectUri, $ontoField, $propertyValue);
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
    private function generateTriple($subjectUri, $ontoField, $value)
    {
        $subject = new NamedNodeImpl($subjectUri);
        $predicate = new NamedNodeImpl($ontoField->getName());
        $object = new NamedNodeImpl($value);

        return new StatementImpl($subject, $predicate, $object);
    }

    /**
     * @param $formData
     * @return mixed
     */
    private function getSubjectUri($formData)
    {
        $title = $formData['OntologyField_'];

        // TODO: clean up title

        return $title;
    }
}
