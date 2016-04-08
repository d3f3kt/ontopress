<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\DataOntology;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyField;
use OntoPress\Entity\Restriction;
use OntoPress\Library\OntoPressTestCase;

class RestrictionTest extends OntoPressTestCase
{
    private $restriction;
    private $ontologyField;

    public function setUp()
    {
        $this->restriction = new Restriction();
        $this->ontologyField = new OntologyField();

        $this->restriction->setName("testName");
        $this->restriction->setOntologyField($this->ontologyField);
    }

    public function tearDown()
    {
        unset($this->restriction);
        unset($this->ontologyField);
    }

    public function testRestrictionBasic()
    {
        $this->assertEquals($this->restriction->getName(), "testName");
        $this->assertNull($this->restriction->getId());
        $this->assertEquals($this->restriction->getOntologyField(), $this->ontologyField);
    }
}
