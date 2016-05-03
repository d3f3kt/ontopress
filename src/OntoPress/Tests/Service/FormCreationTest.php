<?php

namespace OntoPress\Tests;

use OntoPress\Library\OntoPressTestCase;
use OntoPress\Entity\OntologyField;
use OntoPress\Service\SparqlManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use OntoPress\Service\DoctrineManager;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyFile;
use OntoPress\Tests\Entity\OntologyTest;
use OntoPress\Tests\TestHelper;
use OntoPress\Service\FormCreation;

class FormCreationTest extends OntoPressTestCase
{
    private $formCreator;
    private $parser;
    private $doctrine;

    public function setUp()
    {
        parent::setUp();
        $this->doctrine = static::getContainer()->get('doctrine');
        $this->parser = static::getContainer()->get('ontopress.ontology_parser');
        $this->twigGenerator = static::getContainer()->get('ontopress.twig_generator');
        $this->formCreator = static::getContainer()->get('ontopress.form_creation');

    }

    public function tearDown()
    {
        unset($this->formCreator);
        unset($this->parser);
        unset($this->doctrine);
        parent::tearDown();
    }

    public function testFormCreation()
    {
        $ontology = TestHelper::createTestOntology();
        $this->parser->parsing($ontology);
        $this->doctrine->persist($ontology);
        $this->doctrine->flush();

        $form = TestHelper::createOntologyForm($ontology);
        $this->formCreator->create($form);
        // $this->assertEquals($form, $result);
        $this->twigGenerator->generate($form);
    }

    public function testAddField()
    {
        $formBuilder = $this->invokeMethod($this->formCreator, 'getBuilder', array());
        $ontologyFieldText = TestHelper::createOntologyField();
        $ontologyFieldText->setType(OntologyField::TYPE_TEXT);
        $result1 = $this->invokeMethod($this->formCreator, 'addField', array($ontologyFieldText, $formBuilder));
        $this->assertEquals($formBuilder, $result1);
        // $this->assertEquals($result->getForm('TestUri/TestOntologyField')->getName(), 'testLabel');

        $ontologyFieldRadio = TestHelper::createOntologyField();
        $ontologyFieldRadio->setType(OntologyField::TYPE_RADIO);
        $result2 = $this->invokeMethod($this->formCreator, 'addField', array($ontologyFieldRadio, $formBuilder));
        $this->assertEquals($formBuilder, $result2);

        $ontologyFieldSelect = TestHelper::createOntologyField();
        $ontologyFieldSelect->setType(OntologyField::TYPE_SELECT);
        $result3 = $this->invokeMethod($this->formCreator, 'addField', array($ontologyFieldSelect, $formBuilder));
        $this->assertEquals($formBuilder, $result3);
    }

    public function testCreateFilledForm()
    {
        /*
        $resourceUri
        $formValues = $this->get('ontopress.sparql_manager')->getResourceTriples($resourceUri);
        $ontologyForm = TestHelper::createOntologyForm();
        $this->formCreator->createFilledForm($ontologyForm, $formValues);
        */
    }
}
