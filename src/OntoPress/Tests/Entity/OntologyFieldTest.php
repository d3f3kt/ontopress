<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\DataOntology;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyField;
use OntoPress\Entity\OntologyFile;
use OntoPress\Entity\Restriction;
use OntoPress\Library\OntoPressTestCase;

class OntologyFieldTest extends OntoPressTestCase
{
    private $ontologyField;
    private $restriction;
    private $dataOntology;
    private $ontology;

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

    public function tearDown()
    {
        unset($this->ontologyField);
    }

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

    public function testGetUriFile()
    {
        $this->assertEquals($this->ontologyField->getUriFile(), "testname");
    }

    public function testGetFormFieldName()
    {
        $this->assertEquals($this->ontologyField->getFormFieldName(), "_testname");
    }
}
