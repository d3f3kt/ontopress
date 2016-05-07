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
    /**
     * FormController Entity.
     * @var FormController
     */
    private $formController;

    /**
     * Ontology Entity.
     * @var Ontology
     */
    private $ontology;

    /**
     * DataOntology Entity.
     * @var DataOntology
     */
    private $dataOntology;

    /**
     * OntologyField Entity.
     * @var OntologyField
     */
    private $ontologyField;

    /**
     * Form Entity.
     * @var Form
     */
    private $form;

    /**
     * Test setUp.
     * Get called before every test.
     */
    public function setUp()
    {
        $this->ontology = TestHelper::createTestOntology();
        $this->dataOntology = TestHelper::createDataOntology($this->ontology);
        $this->ontologyField = TestHelper::createOntologyField($this->dataOntology);
        $this->form = TestHelper::createOntologyForm($this->ontology);
        $this->formController = new FormController(static::getContainer());

        static::getContainer()->get('doctrine')->persist($this->ontology);
        static::getContainer()->get('doctrine')->persist($this->form);
        static::getContainer()->get('doctrine')->flush();

        parent::setUp();
    }

    /**
     * Test tearDown.
     * Gets called after every test.
     */
    public function tearDow()
    {
        unset($this->ontology);
        unset($this->dataOntology);
        unset($this->ontologyField);
        unset($this->formController);
        unset($this->form);
        parent::tearDown();
    }

    /**
     * Tests showManageAction function, which should create a rendered twig template about form management.
     */
    public function testShowManageAction()
    {
        //test with valid id
        $validId = $this->formController->showManageAction(
            new Request(array(
                'id' => $this->ontology->getId(),
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
                'formId' => $this->form->getId(),
            ))
        );
        $this->assertContains('Formular', $withCorrectId);

        // test whole edit process
        $edit = $this->formController->showEditAction(
            new Request(
                array('formId' => $this->form->getId()),
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
        $this->assertEquals($this->form->getName(), 'otherName');
        $this->assertEquals($this->form->getTwigCode(), '{{ form(form) }}');
    }

    /**
     * Tests showCreateAction function, which should return a rendered twig template about creating a form.
     */
    public function testShowCreateAction()
    {
        Functions::when('wp_get_current_user')->alias(array('OntoPress\Tests\TestHelper', 'emulateWPUser'));

        //test without id
        $withOutId = $this->formController->showCreateAction(new Request());
        $this->assertContains('name="selectOntologyType[ontology]"', $withOutId);

        $withOutIdSubmit = $this->formController->showCreateAction(
            new Request(
                array(),
                array('selectOntologyType' => array(
                    'submit' => '',
                    'ontology' => $this->ontology->getId(),
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
                'ontologyId' => $this->ontology->getId(),
            ))
        );
        $this->assertContains('<label class="required">Ontology fields</label>', $validId);
    }

    /**
     * Tests showCreateFormAction method, should return a rendered twig template.
     */
    public function testShowCreateFormAction()
    {
        Functions::when('wp_get_current_user')->alias(array('OntoPress\Tests\TestHelper', 'emulateWPUser'));

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
                'ontologyId' => $this->ontology->getId(),
            ))
        );
        $this->assertContains('Formular', $withCorrectId);

        $withCorrectIdSubmit = $this->formController->showCreateFormAction(
            new Request(
                array('ontologyId' => $this->ontology->getId()),
                array('createFormType' => array(
                    'name' => 'TestForm_createTest',
                    'ontologyFields' => array($this->ontologyField->getId()),
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
                'id' => $this->form->getId(),
            ))
        );
        $this->assertContains('Formular', $withCorrectId);

        // test whole deletion process
        $deleted = $this->formController->showDeleteAction(
            new Request(
                array('id' => $this->form->getId()),
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
        $this->assertEquals($this->form->getId(), null);
    }
}
