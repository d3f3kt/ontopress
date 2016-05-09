<?php

namespace OntoPress\Tests;

use OntoPress\Library\OntoPressTestCase;
use OntoPress\Entity\OntologyField;
use OntoPress\Service\OntologyParser;
use OntoPress\Service\FormCreation;

/**
 * Class FormCreationTest
 * Tests FormCreation Class.
 */
class FormCreationTest extends OntoPressTestCase
{
    /**
     * FormCreation Instance.
     * @var FormCreation
     */
    private $formCreator;

    /**
     * Parser Entity.
     * @var OntologyParser
     */
    private $parser;

    /**
     *
     * @var
     */
    private $doctrine;

    /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        parent::setUp();
        $this->doctrine = static::getContainer()->get('doctrine');
        $this->parser = static::getContainer()->get('ontopress.ontology_parser');
        $this->twigGenerator = static::getContainer()->get('ontopress.twig_generator');
        $this->formCreator = static::getContainer()->get('ontopress.form_creation');

    }

    /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
    public function tearDown()
    {
        unset($this->formCreator);
        unset($this->parser);
        unset($this->doctrine);
        parent::tearDown();
    }

    /**
     * Tests creation method.
     */
    public function testFormCreation()
    {
        $ontology = TestHelper::createTestOntology();
        $this->parser->parsing($ontology);
        $this->doctrine->persist($ontology);
        $this->doctrine->flush();

        $ontologyField1 = TestHelper::createOntologyField();
        $ontologyField2 = TestHelper::createOntologyField();

        $form = TestHelper::createOntologyForm($ontology);
        $form->addOntologyField($ontologyField1);
        $form->addOntologyField($ontologyField2);
        $this->formCreator->create($form);
        $this->twigGenerator->generate($form);
    }

    /**
     * Tests addField and connected methods.
     */
    public function testAddField()
    {
        $formBuilder = $this->invokeMethod($this->formCreator, 'getBuilder', array());
        $ontologyFieldText = TestHelper::createOntologyField();
        $ontologyFieldText->setType(OntologyField::TYPE_TEXT);
        $result1 = $this->invokeMethod($this->formCreator, 'addField', array($ontologyFieldText, $formBuilder));
        $this->assertEquals($formBuilder, $result1);

        $ontologyFieldRadio = TestHelper::createOntologyField();
        $ontologyFieldRadio->setType(OntologyField::TYPE_RADIO);
        $result2 = $this->invokeMethod($this->formCreator, 'addField', array($ontologyFieldRadio, $formBuilder));
        $this->assertEquals($formBuilder, $result2);

        $ontologyFieldSelect = TestHelper::createOntologyField();
        $ontologyFieldSelect->setType(OntologyField::TYPE_SELECT);
        $result3 = $this->invokeMethod($this->formCreator, 'addField', array($ontologyFieldSelect, $formBuilder));
        $this->assertEquals($formBuilder, $result3);
    }

    /**
     * Tests getBuilder method.
     */
    public function testGetBuilder()
    {
        $this->invokeMethod($this->formCreator, 'getBuilder', array());
    }

    /**
     * Tests getConstraints method.
     */
    public function testGetConstraints()
    {
        $result = $this->invokeMethod($this->formCreator, 'getConstraints', array(TestHelper::createOntologyField()));
        $this->assertEquals(2, count($result));
    }
}
