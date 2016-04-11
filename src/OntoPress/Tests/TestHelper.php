<?php

namespace OntoPress\Tests;

use OntoPress\Entity\Form;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyFile;
use OntoPress\Tests\Entity\OntologyTest;

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
