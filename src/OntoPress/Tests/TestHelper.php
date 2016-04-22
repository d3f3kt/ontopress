<?php

namespace OntoPress\Tests;

use OntoPress\Entity\DataOntology;
use OntoPress\Entity\Form;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyField;
use OntoPress\Entity\OntologyFile;
use OntoPress\Tests\Entity\OntologyTest;
use OntoPress\Entity\Restriction;

class TestHelper
{
    public static function emulateWPUser()
    {
        $testUser = (object) array(
            'ID' => 2,
            'user_login' => 'TestUser',
            'user_email' => 'testUser@example.com',
            'user_firstname' => 'John',
            'user_lastname' => 'Doe',
            'user_nicename' => 'Johni',
            'display_name' => 'Johni',
        );

        return $testUser;
    }

    public static function createTestOntology()
    {
        $ontologyFile1 = new OntologyFile();
        $ontologyFile1->setFile(OntologyTest::createTmpFile('place-ontology.ttl'));

        $ontologyFile2 = new OntologyFile();
        $ontologyFile2->setFile(OntologyTest::createTmpFile('knorke.ttl'));
        $ontology = new Ontology();
        $ontology->setName('Test Ontology')
            ->setAuthor('Test User')
            ->setDate(new \DateTime())
            ->addOntologyFile($ontologyFile1)
            ->addOntologyFile($ontologyFile2);

        $ontology->uploadFiles();

        return $ontology;
    }

    public static function createDataOntology(Ontology $ontology)
    {
        $dataOntology = new DataOntology();
        $dataOntology->setName('Test DataOntology')
           ->setOntology($ontology);
        $ontology->addDataOntology($dataOntology);

        return $dataOntology;
    }

    public static function createOntologyField(DataOntology $dataOntology)
    {
        $restriction = new Restriction();
        $ontologyField = new OntologyField();
        $restriction->setName('Test Restriction');
        $ontologyField->setName('Test OntologyField')
            ->setType(OntologyField::TYPE_TEXT)
            ->setComment('Test Comment')
            ->setLabel('Test Label')
            ->setMandatory(true)
            ->setDataOntology($dataOntology)
            ->setPossessed(true)
            ->addRestriction($restriction);
        $dataOntology->addOntologyField($ontologyField);
        $restriction->setOntologyField($ontologyField);

        return $dataOntology;
    }

    public static function createOntologyForm(Ontology $ontology)
    {
        $form = new Form();
        $form->setName('Test Form')
            ->setAuthor('Test User')
            ->setDate(new \DateTime())
            ->setTwigCode('{{ form(form) }}')
            ->setOntology($ontology);

        foreach ($ontology->getDataOntologies() as $dataOntology) {
            if (strpos($dataOntology->getName(), 'building')) {
                foreach ($dataOntology->getOntologyFields() as $field) {
                    if ($field->getLabel()) {
                        $form->addOntologyField($field);
                    }
                }
            }
        }

        return $form;
    }
}
