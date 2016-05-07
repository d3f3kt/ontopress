<?php

namespace OntoPress\Tests;

use OntoPress\Entity\Form;
use OntoPress\Entity\OntologyField;
use OntoPress\Library\OntoPressTestCase;
use OntoPress\Service\RestrictionHelper;
use OntoPress\Service\TwigGenerator;

/**
 * Class TwigGeneratorTest
 * Tests the twig generator.
 */
class TwigGeneratorTest extends OntoPressTestCase
{
    /**
     * @var twigGenerator
     */
    private $twigGenerator;

    /**
     * OntologyField Entity.
     *
     * @var ontologyField
     */
    private $ontologyField;

    /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        parent::setUp();
        $this->twigGenerator = static::getContainer()->get('ontopress.twig_generator');
        $this->ontologyField = TestHelper::createOntologyField();
    }

    /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
    public function tearDown()
    {
        unset($ontologyField);
        unset($twigGenerator);
        parent::tearDown();
    }

    /**
     * Test generate method, should generate a twig structure of an OntoPressForm and return it as string.
     */
    public function testGenerate()
    {
        $result = $this->twigGenerator->generate(new Form());
        $this->assertContains('OntoPressForm[submit]', $result);
    }

    /**
     * Test generateFormField method, should test the rendering of different form field types
     */
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

    /**
     * Test GenerateFieldValue method, should test the generation of a value tag for a text field.
     */
    public function testGenerateFieldValue()
    {
        $result = $this->invokeMethod($this->twigGenerator, 'generateFieldValue', array($this->ontologyField));
        // $this->assertContains('required', $result);
        $this->assertEquals(1, count($result));
    }

    /**
     * Test GenerateChoiceAttributes method, should test the generation of name and id for a radio field.
     */
    public function testGenerateChoiceAttributes()
    {
        $result = $this->invokeMethod($this->twigGenerator, 'generateChoiceAttributes', array($this->ontologyField));
        $this->assertEquals('id="OntoPressForm_'.$this->ontologyField->getFormFieldName().'_%id%" '.
            'name="OntoPressForm['.$this->ontologyField->getFormFieldName().']"', $result);
    }

    /**
     * Test createFieldVarName method, should create var name for an ontology field.
     */
    public function testCreateFieldVarName()
    {
        $result = $this->invokeMethod($this->twigGenerator, 'createFieldVarName', array($this->ontologyField));
        $this->assertEquals('form.children.'.$this->ontologyField->getFormFieldName().'.vars', $result);
    }
}
