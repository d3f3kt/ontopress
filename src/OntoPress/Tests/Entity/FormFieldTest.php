<?php

namespace OntoPress\Tests\Entity;

//use OntoPress\Entity\Form;
use OntoPress\Entity\FormField;
use OntoPress\Library\OntoPressTestCase;

class FormFieldTest extends OntoPressTestCase
{
    private $formField;

    public function setUp()
    {
        $this->formField = new FormField();
        $this->formField->setFieldUri('TestUri');
        $this->formField->setForm();
    }

    public function tearDown()
    {
        unset($this->formField);
    }

    public function testFormFieldBasics()
    {
        $this->assertEquals($this->formField->getFieldUri(), 'TestUri');
    }
}
