<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\Form;
//use OntoPress\Entity\FormField;
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
    }

    public function tearDown()
    {
        unset($this->form);
    }

    // noch getOntology, getTwigCode usw hier auch testen? alle getter und setter?
    public function testFormBasics()
    {
        $this->assertEquals($this->form->getName(), 'TestForm');
        $this->assertEquals($this->form->getAuthor(), 'TestAuthor');
        $this->assertEquals($this->form->getDate(), $this->dummyDate);
    }
}
