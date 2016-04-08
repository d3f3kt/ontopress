<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\Form;
use OntoPress\Entity\Ontology;
use OntoPress\Library\OntoPressTestCase;

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


    public function setUp()
    {
        $this->dummyDate = new \DateTime();

        $this->form = new Form();
        $this->form->setName('TestForm')
            ->setAuthor('TestAuthor')
            ->setDate($this->dummyDate);
        $this->form->setTwigCode('TestTwig');

        $this->ontology = null;
        $this->form->setOntology($this->ontology);
    }

    public function tearDown()
    {
        unset($this->form);
    }

    public function testFormBasics()
    {
        $this->assertEquals($this->form->getName(), 'TestForm');
        $this->assertEquals($this->form->getAuthor(), 'TestAuthor');
        $this->assertEquals($this->form->getDate(), $this->dummyDate);
        $this->assertEquals($this->form->getTwigCode(), 'TestTwig');
        $this->assertNull($this->form->getOntology(), '');
    }
    /* for add/removeFormField create Formfield object and give it to the functions
    public function testAddRemoveOntologyField()
    {
        $this->form->addOntologyField($this->ontologyField);
        // test add
        $this->form->removeOntologyField($this->ontologyField);
        // test remove

        //test getFormFields()
    }
    */
}
