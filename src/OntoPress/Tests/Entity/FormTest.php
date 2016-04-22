<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\Form;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\DataOntology;
use OntoPress\Entity\OntologyField;
use OntoPress\Library\OntoPressTestCase;
use OntoPress\Tests\TestHelper;

/**
 * Class FormTest
 * Creates a Form Entity and its surroundings and tests it.
 */
class FormTest extends OntoPressTestCase
{
    /**
     * Form Entity.
     *
     * @var Form
     */
    private $form;

    /**
     * OntologyField Entity.
     *
     * @var OntologyField
     */
    private $ontologyField;

    /**
     * Ontology Entity.
     *
     * @var Ontology
     */
    private $ontology;

    /**
     * DataOntology Entity.
     * @var DataOntology
     */
    private $dataOntology;

    /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        parent::setUp();
        $this->ontology = TestHelper::createTestOntology();
        $this->dataOntology = TestHelper::createDataOntology($this->ontology);
        $this->ontologyField = TestHelper::createOntologyField($this->dataOntology);
        $this->form = TestHelper::createOntologyForm($this->ontology, $this->ontologyField);
    }

    /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
    public function tearDown()
    {
        unset($this->form);
        unset($this->ontology);
        unset($this->ontologyField);
        unset($this->dummyDate);
        parent::tearDown();
    }

    /**
     * Tests Basic set/get-methods of the Form Entity, which should return the new or changed attributes.
     */
    public function testFormBasic()
    {
        $this->assertEquals($this->form->getName(), 'Test Form');
        $this->assertEquals($this->form->getAuthor(), 'Test User');
        $this->assertEquals($this->form->getDate(), new \DateTime());
        $this->assertEquals($this->form->getTwigCode(), '{{ form(form) }}');

        $this->assertEquals($this->form->getOntologyFields()[0], $this->ontologyField);

        $this->form->removeOntologyField($this->ontologyField);
        $this->assertEmpty($this->form->getOntologyFields());

        $this->assertEquals($this->form->getOntology(), $this->ontology);
    }
}
