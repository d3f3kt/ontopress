<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\Form;
use OntoPress\Entity\FormField;
use OntoPress\Library\OntoPressTestCase;

class FormFieldTest extends OntoPressTestCase
{
    private $formField;
    private $form;

    public function setUp()
    {
        $this->formField = new FormField();
        $this->form = new Form();
        $this->formField->setFieldUri('TestUri');
        $this->formField->setForm($this->form);
    }

    public function tearDown()
    {
        unset($this->formField);
    }

    public function testFormFieldBasics()
    {
        $this->assertEquals($this->formField->getFieldUri(), 'TestUri');
        $this->assertEquals($this->formField->getForm(), $this->form);
    }
}
