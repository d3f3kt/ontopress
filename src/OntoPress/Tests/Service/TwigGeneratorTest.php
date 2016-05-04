<?php

namespace OntoPress\Tests;

use OntoPress\Entity\Form;
use OntoPress\Entity\OntologyField;
use OntoPress\Library\OntoPressTestCase;
use OntoPress\Service\RestrictionHelper;
use OntoPress\Service\TwigGenerator;

class TwigGeneratorTest extends OntoPressTestCase
{
    private $twigGenerator;
    private $ontologyField;

    public function setUp()
    {
        parent::setUp();
        $this->twigGenerator = static::getContainer()->get('ontopress.twig_generator');
        $this->ontologyField = TestHelper::createOntologyField();
    }

    public function tearDown()
    {
        unset($ontologyField);
        unset($twigGenerator);
        parent::tearDown();
    }

    public function testGenerate()
    {
        $result = $this->twigGenerator->generate(new Form());
        $this->assertContains('OntoPressForm[submit]', $result);
    }

    public function testGenerateFormField()
    {
        $this->ontologyField->setType(OntologyField::TYPE_TEXT);
        $resultText = $this->invokeMethod($this->twigGenerator, 'generateFormField', array($this->ontologyField));
        $this->assertContains('text', $resultText);

        $this->ontologyField->setType(OntologyField::TYPE_RADIO);
        $resultRadio = $this->invokeMethod($this->twigGenerator, 'generateFormField', array($this->ontologyField));
        $this->assertContains('radio', $resultRadio);

        $this->ontologyField->setType(OntologyField::TYPE_SELECT);
        $resultSelect = $this->invokeMethod($this->twigGenerator, 'generateFormField', array($this->ontologyField));
        $this->assertContains('select', $resultSelect);

        // generateLabel fail test
    }

    public function generateFieldValue()
    {
        $result = $this->invokeMethod($this->twigGenerator, 'generateFieldValue', array($this->ontologyField));
        $this->assertContains('id=OntoPressForm_', $result);
    }
}
