<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\DataOntology;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyField;
use OntoPress\Entity\Restriction;
use OntoPress\Library\OntoPressTestCase;
use OntoPress\Tests\TestHelper;

/**
 * Class OntologyFieldTest
 * Creates an OntologyField, with related DataOntology and Restriction, and tests it
 */
class OntologyFieldTest extends OntoPressTestCase
{
    /**
     * OntologyField Entity
     * @var OntologyField
     */
    private $ontologyField;

    /**
     * DataOntology Entity
     * @var DataOntology
     */
    private $dataOntology;

    /**
     * Ontology Entity
     * @var Ontology
     */
    private $ontology;

    /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        parent::setUp();
        $this->ontology = TestHelper::createTestOntology();
        $this->dataOntology = TestHelper::createDataOntology($this->ontology);
        $this->ontologyField = TestHelper::createOntologyField($this->dataOntology);
    }

    /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
    public function tearDown()
    {
        unset($this->ontologyField);
        unset($this->dataOntology);
        unset($this->ontology);
        parent::tearDown();
    }

    /**
     * Tests all Basic set/get-methods, which should return the new or changed attributes.
     */
    public function testOntologyFieldBasic()
    {
        $restriction = new Restriction();
        $this->ontologyField->addRestriction($restriction);

        $this->assertEquals($this->ontologyField->getName(), "TestUri/TestOntologyField");
        $this->assertEquals($this->ontologyField->getComment(), "Test Comment");
        $this->assertEquals($this->ontologyField->getLabel(), "Test Label");
        $this->assertTrue($this->ontologyField->getMandatory());
        $this->assertEquals($this->ontologyField->getType(), "TEXT");
        $this->assertEquals($this->ontologyField->getRestrictions()[1], $restriction);
        $this->assertEquals($this->ontologyField->getDataOntology(), $this->dataOntology);

        $this->ontologyField->removeRestriction(new Restriction());
        $this->assertEquals($this->ontologyField->getRestrictions()->count(), 2);
    }

    /**
     * Tests getUriFile method, which should return the filename.
     */
    public function testGetUriFile()
    {
        $this->assertEquals($this->ontologyField->getUriFile(), "TestOntologyField");
    }

    /**
     * Tests getFormFieldName method, which should return the filename with small adaptations.
     */
    public function testGetFormFieldName()
    {
        $this->assertEquals($this->ontologyField->getFormFieldName(), "OntologyField_");
    }
}
