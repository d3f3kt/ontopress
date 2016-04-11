<?php

namespace OntoPress\Tests;

use OntoPress\Library\OntoPressTestCase;
use OntoPress\Service\DoctrineManager;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyFile;
use OntoPress\Tests\Entity\OntologyTest;
use OntoPress\Tests\TestHelper;

class FormCreationTest extends OntoPressTestCase
{
    private $formCreation;
    private $parser;
    private $doctrine;

    public function setUp()
    {
        parent::setUp();
        $this->doctrine = static::getContainer()->get('doctrine');
        $this->parser = static::getContainer()->get('ontopress.ontology_parser');
        $this->formCreation = static::getContainer()->get('ontopress.form_creation');
        $this->twigGenerator = static::getContainer()->get('ontopress.twig_generator');
    }

    public function tearDown()
    {
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

        $this->formCreation->create($form);
        $this->twigGenerator->generate($form);

    }
}
