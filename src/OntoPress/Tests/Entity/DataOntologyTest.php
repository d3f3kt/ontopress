<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\DataOntology;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyField;
use OntoPress\Library\OntoPressTestCase;

class DataOntologyTest extends OntoPressTestCase
{
    private $dataOntology;
    private $ontology;
    private $ontologyField;

    public function setUp()
    {
        $this->dataOntology = new DataOntology();
        $this->ontology = new Ontology();
        $this->ontologyField = new OntologyField();

        $this->dataOntology->setName("testName");
        $this->dataOntology->setOntology($this->ontology);
        $this->dataOntology->addOntologyField($this->ontologyField);
    }

    public function tearDown()
    {
        unset($this->dataOntology);
        unset($this->ontology);
        unset($this->ontologyField);
    }

    public function testDataOntologyBasic()
    {
        $this->assertEquals($this->dataOntology->getName(), "testName");
        $this->assertEquals($this->dataOntology->getOntology(), $this->ontology);
        $this->assertEquals($this->dataOntology->getOntologyFields()[0], $this->ontologyField);

        $this->dataOntology->removeOntologyField($this->ontologyField);
        $this->assertEmpty($this->dataOntology->getOntologyFields());
    }
}
