<?php

namespace OntoPress\Tests;

use OntoPress\Entity\DataOntology;
use OntoPress\Entity\Form;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyField;
use OntoPress\Entity\OntologyFile;
use OntoPress\Tests\Entity\OntologyTest;
use OntoPress\Entity\Restriction;

/**
 * Class TestHelper
 * Class helping tests to create entities, with connected attributes.
 */
class TestHelper
{
    /**
     * creates a test user
     *
     * @return object
     */
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

    /**
     * creates a test Ontology
     *
     * @param bool $withOntologyFiles
     *
     * @return Ontology
     */
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

    /**
     * creates a data ontology for the testOntology
     *
     * @param Ontology|null $ontology
     *
     * @return DataOntology
     */
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

    /**
     * creates an ontology field for the testOntology
     *
     * @param DataOntology|null $dataOntology
     * @param Restriction|null $restriction
     *
     * @return OntologyField
     */
    public static function createOntologyField(DataOntology $dataOntology = null, Restriction $restriction = null)
    {
        if (!$restriction) {
            $restriction = new Restriction();
            $restriction->setName('Test Restriction');
        }

        if (!$dataOntology) {
            $dataOntology = new DataOntology();
            $dataOntology->setName('Test DataOntology');
        }

        $ontologyField = new OntologyField();
        $ontologyField
            ->setName('TestUri/TestOntologyField')
            ->setType(OntologyField::TYPE_TEXT)
            ->setComment('Test Comment')
            ->setLabel('Test Label')
            ->setMandatory(true)
            ->setDataOntology($dataOntology)
            ->setRegex('Test Regex')
            ->setPossessed(true)
            ->addRestriction($restriction);

        $dataOntology->addOntologyField($ontologyField);
        $restriction->setOntologyField($ontologyField);

        return $ontologyField;
    }

    /**
     * creates a test form
     *
     * @param Ontology|null $ontology
     * @param OntologyField|null $ontologyField
     *
     * @return Form
     */
    public static function createOntologyForm(Ontology $ontology = null, OntologyField $ontologyField = null)
    {
        if (!$ontology) {
            $ontology = new Ontology();
            $ontology
                ->setName('Test Ontology '. uniqid())
                ->setAuthor('Test User')
                ->setDate(new \DateTime());
        }

        $form = new Form();
        $form
            ->setName('Test Form')
            ->setAuthor('Test User')
            ->setDate(new \DateTime())
            ->setTwigCode('{{ form(form) }}')
            ->setOntology($ontology);
        $ontology->addOntologyForm($form);

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
