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

    public static function createTestOntology($withOntologyFiles = true)
    {
        $ontology = new Ontology();
        $ontology
            ->setName('Test Ontology '. uniqid())
            ->setAuthor('Test User')
            ->setDate(new \DateTime());


        if ($withOntologyFiles) {
            $ontologyFile1 = new OntologyFile();
            $ontologyFile1->setFile(OntologyTest::createTmpFile('place-ontology.ttl'));
            $ontologyFile2 = new OntologyFile();
            $ontologyFile2->setFile(OntologyTest::createTmpFile('knorke.ttl'));

            $ontology
                ->addOntologyFile($ontologyFile1)
                ->addOntologyFile($ontologyFile2);

            $ontology->uploadFiles();
        }

        return $ontology;
    }

    public static function createDataOntology(Ontology $ontology = null)
    {
        if (!$ontology) {
            $ontology = new Ontology();
        }
        $dataOntology = new DataOntology();
        $dataOntology
            ->setName('Test DataOntology')
            ->setOntology($ontology);
        $ontology->addDataOntology($dataOntology);

        return $dataOntology;
    }

    public static function createOntologyField(DataOntology $dataOntology, Restriction $restriction = null)
    {
        if (!$restriction) {
            $restriction = new Restriction();
            $restriction->setName('Test Restriction');
        }

        $ontologyField = new OntologyField();
        $ontologyField
            ->setName('TestUri/TestOntologyField')
            ->setType(OntologyField::TYPE_TEXT)
            ->setComment('Test Comment')
            ->setLabel('Test Label')
            ->setMandatory(true)
            ->setDataOntology($dataOntology)
            ->setPossessed(true)
            ->addRestriction($restriction);

        $dataOntology->addOntologyField($ontologyField);
        $restriction->setOntologyField($ontologyField);

        return $ontologyField;
    }

    public static function createOntologyForm(Ontology $ontology, OntologyField $ontologyField = null)
    {
        $form = new Form();
        $form
            ->setName('Test Form')
            ->setAuthor('Test User')
            ->setDate(new \DateTime())
            ->setTwigCode('{{ form(form) }}')
            ->setOntology($ontology);

        if (!$ontologyField) {
            foreach ($ontology->getDataOntologies() as $dataOntology) {
                if (strpos($dataOntology->getName(), 'building')) {
                    foreach ($dataOntology->getOntologyFields() as $field) {
                        if ($field->getLabel()) {
                            $form->addOntologyField($field);
                        }
                    }
                }
            }
        } else {
            $form->addOntologyField($ontologyField);
        }

        return $form;
    }
}
