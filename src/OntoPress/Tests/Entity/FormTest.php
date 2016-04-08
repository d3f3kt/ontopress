<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\Form;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyField;
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
        $this->form = new Form();
        $this->dummyDate = new \DateTime();
        $this->ontology = new Ontology();
        $this->ontologyField = new OntologyField();

        $this->form->setName('TestForm')
                    ->setAuthor('TestAuthor')
                    ->setDate($this->dummyDate)
                    ->setTwigCode('TestTwig')
                    ->setOntology($this->ontology);
        //           ->addOntologyField($this->ontologyField);
    }

    public function tearDown()
    {
        unset($this->form);
        unset($this->ontology);
        unset($this->ontologyField);
        unset($this->dummyDate);
    }

    public function testFormBasic()
    {
        $this->assertEquals($this->form->getName(), 'TestForm');
        $this->assertEquals($this->form->getAuthor(), 'TestAuthor');
        $this->assertEquals($this->form->getDate(), $this->dummyDate);
        $this->assertEquals($this->form->getTwigCode(), 'TestTwig');
        $this->assertEquals($this->form->getOntology(), $this->ontology);
        /*
        $this->assertEquals($this->form->getOntologyFields()[0], $this->ontologyField);

        $this->form->removeOntologyField($this->ontologyField);
        $this->assertEmpty($this->form->getOntologyFields());
        */}
}
