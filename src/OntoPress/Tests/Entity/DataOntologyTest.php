<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\DataOntology;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyField;
use OntoPress\Library\OntoPressTestCase;

/**
 * Class DataOntologyTest
 * Creates a DataOntology and surroundings and tests them.
 */
class DataOntologyTest extends OntoPressTestCase
{
    /**
     * DataOntology Entity.
     * @var DataOntology
     */
    private $dataOntology;

    /**
     * Ontology Entity.
     * @var Ontology
     */
    private $ontology;

    /**
     * OntologyField Entity.
     * @var OntologyField
     */
    private $ontologyField;

    /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        $this->dataOntology = new DataOntology();
        $this->ontology = new Ontology();
        $this->ontologyField = new OntologyField();

        $this->dataOntology->setName("testName");
        $this->dataOntology->setOntology($this->ontology);
        $this->dataOntology->addOntologyField($this->ontologyField);
    }

    /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
    public function tearDown()
    {
        unset($this->dataOntology);
        unset($this->ontology);
        unset($this->ontologyField);
    }

    /**
     * Tests DataOntology basic set/get-methods, which should return the new or changed attributes.
     * Id variable cant be set, so its null in the test.
     */
    public function testDataOntologyBasic()
    {
        $this->assertEquals($this->dataOntology->getName(), "testName");
        $this->assertEquals($this->dataOntology->getOntology(), $this->ontology);
        $this->assertEquals($this->dataOntology->getOntologyFields()[0], $this->ontologyField);
        $this->assertNull($this->dataOntology->getId());

        $this->dataOntology->removeOntologyField($this->ontologyField);
        $this->assertEmpty($this->dataOntology->getOntologyFields());
    }
}
