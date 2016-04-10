<?php

namespace OntoPress\Tests;

use Brain\Monkey\Functions;
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
     *
     * @expectedException BadMethodCallException
     *
     */
    public function testShowEditAction()
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
        $withOutId = $this->formController->showEditAction(new Request());
        $this->assertContains("window.location", $withOutId);

        //test with wrong id
        $wrongId = $this->formController->showEditAction(
            new Request(array(
                'id' => 1337,
            ))
        );
        $this->assertContains("window.location",$wrongId);

        // test with correct id
        $withCorrectId = $this->formController->showEditAction(
            new Request(array(
                'formId' => $testForm->getId(),
            ))
        );
        $this->assertContains('Formular', $withCorrectId);

        // test whole edit process
        $edit = $this->formController->showEditAction(
            new Request(
                array('formId' => $testForm->getId()),
                array('EditFormType' => array(
                    'submit' => '',
                )),
                array(),
                array(),
                array(),
                array('REQUEST_METHOD' => 'POST')
            )
        );

        $this->assertContains('a', $edit);
        $this->assertEquals($testForm->getId(), 1);
    }

    /**
     * Tests showCreateAction function, which should return a rendered twig template about creating a form.
     */
    public function testShowCreateAction()
    {
        // create test ontology
        $testOntology = new Ontology();
        $testOntology->setName('TestOntoloy')
            ->setAuthor('testAuthor')
            ->setDate(new \DateTime());
        static::getContainer()->get('doctrine')->persist($testOntology);
        static::getContainer()->get('doctrine')->flush();

        //test without id
        $withOutId = $this->formController->showCreateAction(new Request());

        $this->assertContains("Formular Anlegen", $withOutId);

        //test with valid id
        $validId = $this->formController->showCreateAction(
            new Request(array(
                'id' => $testOntology->getId(),
            ))
        );
        $this->assertContains("Formular Anlegen", $validId);
    }
    public function testShowCreateFormAction()
    {
        // create test ontology
        $testOntology = new Ontology();
        $testOntology->setName('TestOntoloy')
            ->setAuthor('testAuthor')
            ->setDate(new \DateTime());
        static::getContainer()->get('doctrine')->persist($testOntology);
        static::getContainer()->get('doctrine')->flush();


        $withOutId = $this->formController->showCreateFormAction(new Request());
        $this->assertContains("kein Formular", $withOutId);

        //test with wrong id
        $wrongId = $this->formController->showCreateFormAction(
            new Request(array(
                'id' => 1337,
            ))
        );
        $this->assertContains("kein Formular ", $wrongId);

        // test with correct id
        $withCorrectId = $this->formController->showCreateFormAction(
            new Request(array(
                'id' => $testOntology->getId(),
            ))
        );
        $this->assertContains('Formular', $withCorrectId);



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

        $this->assertContains('window.location', $deleted);
        $this->assertEquals($testForm->getId(), null);

    }
}
