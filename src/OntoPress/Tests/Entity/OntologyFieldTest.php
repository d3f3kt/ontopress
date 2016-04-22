<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\DataOntology;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyField;
use OntoPress\Entity\Restriction;
use OntoPress\Library\OntoPressTestCase;

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
     * Restriction Entity
     * @var Restriction
     */
    private $restriction;

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
        $this->dataOntology = new DataOntology();
        $this->ontology = new Ontology();
        $this->dataOntology->setOntology($this->ontology);
        $this->restriction = new Restriction();
        $this->ontologyField = new OntologyField();
        $this->ontologyField->setDataOntology($this->dataOntology)
                            ->setName("test/testroot/testname")
                            ->setComment("testComment")
                            ->setLabel("testLabel")
                            ->setMandatory(true)
                            ->setType("TYPE_TEXT")
                            ->addRestriction($this->restriction);
    }

    /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
    public function tearDown()
    {
        unset($this->ontologyField);
        unset($this->dataOntology);
        unset($this->restriction);
        unset($this->ontology);
    }

    /**
     * Tests all Basic set/get-methods, which should return the new or changed attributes.
     */
    public function testOntologyFieldBasic()
    {
        $this->assertEquals($this->ontologyField->getName(), "test/testroot/testname");
        $this->assertEquals($this->ontologyField->getComment(), "testComment");
        $this->assertEquals($this->ontologyField->getLabel(), "testLabel");
        $this->assertTrue($this->ontologyField->getMandatory());
        $this->assertEquals($this->ontologyField->getType(), "TYPE_TEXT");
        $this->assertEquals($this->ontologyField->getRestrictions()[0], $this->restriction);
        $this->assertEquals($this->ontologyField->getDataOntology(), $this->dataOntology);

        $this->ontologyField->removeRestriction($this->restriction);
        $this->assertEmpty($this->ontologyField->getRestrictions());
    }

    /**
     * Tests getUriFile method, which should return the filename.
     */
    public function testGetUriFile()
    {
        $this->assertEquals($this->ontologyField->getUriFile(), "testname");
    }

    /**
     * Tests getFormFieldName method, which should return the filename with small adaptations.
     */
    public function testGetFormFieldName()
    {
        $this->assertEquals($this->ontologyField->getFormFieldName(), "OntologyField_");
    }
}
