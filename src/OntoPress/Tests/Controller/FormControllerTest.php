<?php

namespace OntoPress\Tests;

use OntoPress\Controller\FormController;
use OntoPress\Entity\Form;
use OntoPress\Entity\Ontology;
use OntoPress\Library\OntoPressTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FormControllerTest
 * Creates a FormController and tests it.
 */
class FormControllerTest extends OntoPressTestCase
{

    private $formController;

    public function setUp()
    {
        parent::setUp();
        $this->formController = new FormController(static::getContainer());
    }

    public function tearDow()
    {
        unset($this->formController);
        parent::tearDown();
    }

    /**
     * Tests showManageAction function, which should create a rendered twig template about form management.
     */
    public function testShowManageAction()
    {
        // create test form
        $testOntology = new Ontology();
        $testOntology->setName('TestOntoloy')
            ->setAuthor('testAuthor')
            ->setDate(new \DateTime());
        static::getContainer()->get('doctrine')->persist($testOntology);
        static::getContainer()->get('doctrine')->flush();

        //test with valid id
        $validId = $this->formController->showManageAction(
            new Request(array(
                'id' => $testOntology->getId(),
            ))
        );
        $this->assertContains("Formular Verwaltung", $validId);

        //test without id
        $withOutId = $this->formController->showManageAction(new Request());

        $this->assertContains("Formular Verwaltung", $withOutId);
    }

    /**
     * Tests showEditAction function, which should return a rendered twig template about form edits.
     */
    public function testShowEditAction()
    {
        $container = static::getContainer();
        $formController = new FormController($container);
        $formOutput = $formController->showEditAction();

        $this->assertContains("Formular Bearbeiten", $formOutput);
    }

    /**
     * Tests showCreateAction function, which should return a rendered twig template about creating a form.
     */
    public function testShowCreateAction()
    {
        $container = static::getContainer();
        $formController = new FormController($container);
        $formOutput = $formController->showCreateAction();

        $this->assertContains("Formular Anlegen", $formOutput);
    }

    /**
     * Tests showDeleteAction function, which should return a rendered twig template about deleting a form.
     */
    public function testShowDeleteAction()
    {
        // create test ontology
        $testOntology = new Ontology();
        $testOntology->setName('TestOntoloy')
            ->setAuthor('testAuthor')
            ->setDate(new \DateTime());
        static::getContainer()->get('doctrine')->persist($testOntology);
        static::getContainer()->get('doctrine')->flush();
        
        // create test form
        $testForm = new Form();
        $testForm->setName('TestForm')
            ->setTwigCode('testTwigCode')
            ->setAuthor('testAuthor')
            ->setDate(new \DateTime())
            ->setOntology($testOntology);
        static::getContainer()->get('doctrine')->persist($testForm);
        static::getContainer()->get('doctrine')->flush();
        
        //test without id
        $withOutId = $this->formController->showDeleteAction(new Request());
        $this->assertContains("kein Formular", $withOutId);
        
        //test with wrong id
        $wrongId = $this->formController->showDeleteAction(
            new Request(array(
                'id' => 1337,
            ))
        );
        $this->assertContains("kein Formular ", $wrongId);

        // test with correct id
        $withCorrectId = $this->formController->showDeleteAction(
            new Request(array(
                'id' => $testForm->getId(),
            ))
        );
        $this->assertContains('Formular', $withCorrectId);
        
        // test whole deletion process
        $deleted = $this->formController->showDeleteAction(
            new Request(
                array('id' => $testForm->getId()),
                array('formDeleteType' => array(
                    'submit' => '',
                )),
                array(),
                array(),
                array(),
                array('REQUEST_METHOD' => 'POST')
            )
        );
        echo $deleted;
        $this->assertContains('window.location', $deleted);
        $this->assertEquals($testForm->getId(), null);

    }
}
