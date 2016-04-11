<?php

namespace OntoPress\Tests;

use Brain\Monkey\Functions;
use OntoPress\Controller\FormController;
use OntoPress\Entity\Form;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\DataOntology;
use OntoPress\Entity\OntologyField;
use OntoPress\Library\OntoPressWPTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FormControllerTest
 * Creates a FormController and tests it.
 */
class FormControllerTest extends OntoPressWPTestCase
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
        $this->assertContains('Formular Verwaltung', $validId);

        //test without id
        $withOutId = $this->formController->showManageAction(new Request());

        $this->assertContains('Formular Verwaltung', $withOutId);
    }

    /**
     * Tests showEditAction function, which should return a rendered twig template about form edits.
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
        $this->assertContains('window.location', $withOutId);

        //test with wrong id
        $wrongId = $this->formController->showEditAction(
            new Request(array(
                'formId' => 1337,
            ))
        );
        $this->assertContains('Formular nicht gefunden!', $wrongId);

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
                array('formEditType' => array(
                    'submit' => '',
                    'name' => 'otherName',
                    'twigCode' => '{{ form(form) }}',
                )),
                array(),
                array(),
                array(),
                array('REQUEST_METHOD' => 'POST')
            )
        );

        $this->assertContains('window.location', $edit);
        $this->assertEquals($testForm->getName(), 'otherName');
        $this->assertEquals($testForm->getTwigCode(), '{{ form(form) }}');
    }

    /**
     * Tests showCreateAction function, which should return a rendered twig template about creating a form.
     */
    public function testShowCreateAction()
    {
        Functions::when('wp_get_current_user')->alias(array('OntoPress\Tests\TestHelper', 'emulateWPUser'));

        // create test ontology
        $testOntology = new Ontology();
        $testOntology->setName('TestOntoloy')
            ->setAuthor('testAuthor')
            ->setDate(new \DateTime());
        static::getContainer()->get('doctrine')->persist($testOntology);
        static::getContainer()->get('doctrine')->flush();

        //test without id
        $withOutId = $this->formController->showCreateAction(new Request());
        $this->assertContains('name="selectOntologyType[ontology]"', $withOutId);

        $withOutIdSubmit = $this->formController->showCreateAction(
            new Request(
                array(),
                array('selectOntologyType' => array(
                    'submit' => '',
                    'ontology' => $testOntology->getId(),
                )),
                array(),
                array(),
                array(),
                array('REQUEST_METHOD' => 'POST')
            )
        );
        $this->assertContains('window.location', $withOutIdSubmit);

        //test with valid id
        $validId = $this->formController->showCreateAction(
            new Request(array(
                'ontologyId' => $testOntology->getId(),
            ))
        );
        $this->assertContains('<label class="required">Ontology fields</label>', $validId);
    }

    public function testShowCreateFormAction()
    {
        Functions::when('wp_get_current_user')->alias(array('OntoPress\Tests\TestHelper', 'emulateWPUser'));

        // create test ontology
        $testOntologyField = new OntologyField();
        $testOntologyField->setName('http://localhost/testField')
            ->setLabel('testField')
            ->setType(OntologyField::TYPE_TEXT);

        $testDataOntology = new DataOntology();
        $testDataOntology->setName('TestDataOntology')
            ->addOntologyField($testOntologyField);

        $testOntology = new Ontology();
        $testOntology->setName('TestOntologyField')
            ->setAuthor('testAuthor')
            ->setDate(new \DateTime())
            ->addDataOntology($testDataOntology);

        static::getContainer()->get('doctrine')->persist($testOntology);
        static::getContainer()->get('doctrine')->flush();

        $withOutId = $this->formController->showCreateFormAction(new Request());
        $this->assertContains('window.location', $withOutId);

        //test with wrong id
        $wrongId = $this->formController->showCreateFormAction(
            new Request(array(
                'ontologyId' => 1337,
            ))
        );
        $this->assertContains('Ontology nicht gefunden!', $wrongId);

        // test with correct id
        $withCorrectId = $this->formController->showCreateFormAction(
            new Request(array(
                'ontologyId' => $testOntology->getId(),
            ))
        );
        $this->assertContains('Formular', $withCorrectId);

        $withCorrectIdSubmit = $this->formController->showCreateFormAction(
            new Request(
                array('ontologyId' => $testOntology->getId()),
                array('createFormType' => array(
                    'name' => 'TestForm_createTest',
                    'ontologyFields' => array($testOntologyField->getId()),
                    'submit' => '',
                )),
                array(),
                array(),
                array(),
                array('REQUEST_METHOD' => 'POST')
            )
        );
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
        $this->assertContains('kein Formular', $withOutId);

        //test with wrong id
        $wrongId = $this->formController->showDeleteAction(
            new Request(array(
                'id' => 1337,
            ))
        );
        $this->assertContains('kein Formular ', $wrongId);

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
