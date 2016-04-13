<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\Form;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyField;
use OntoPress\Library\OntoPressTestCase;

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
     * Dummy Date.
     *
     * @var \DateTime
     */
    private $dummyDate;


    /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        $this->form = new Form();
        $this->dummyDate = new \DateTime();
        $this->ontology = new Ontology();
        $this->ontologyField = new OntologyField();
        $this->ontologyField->setName('Test Field');

        $this->form->setName('TestForm')
                    ->setAuthor('TestAuthor')
                    ->setDate($this->dummyDate)
                    ->setTwigCode('TestTwig')
                    ->setOntology($this->ontology)
                    ->addOntologyField($this->ontologyField);
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
    }

    /**
     * Tests Basic set/get-methods of the Form Entity, which should return the new or changed attributes.
     */
    public function testFormBasic()
    {
        $this->assertEquals($this->form->getName(), 'TestForm');
        $this->assertEquals($this->form->getAuthor(), 'TestAuthor');
        $this->assertEquals($this->form->getDate(), $this->dummyDate);
        $this->assertEquals($this->form->getTwigCode(), 'TestTwig');

        $this->assertEquals($this->form->getOntologyFields()[0], $this->ontologyField);

        $this->form->removeOntologyField($this->ontologyField);
        $this->assertEmpty($this->form->getOntologyFields());

        $this->assertEquals($this->form->getOntology(), $this->ontology);
    }
}
