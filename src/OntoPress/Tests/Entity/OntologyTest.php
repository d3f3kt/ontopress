<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\Ontology;
use OntoPress\Libary\OntoPressTestCase;

class OntologyTest extends OntoPressTestCase
{
    public $ontologyTest;

    public function setUp()
    {
        $this->ontologyTest = new Ontology();
        $ontologyTest->setName("TestOntology");
        $ontologyTest->setAuthor("TestAuthor");
        $ontologyTest->setDate("01.01.2011");
    }

    public function testName()
    {
        $testOntology = $this->ontologyTest->getName();
        $this->assertTrue($testOntology == "TestOntology");
    }

    public function testAuthor()
    {
        $testAuthor = $this->ontologyTest->getAuthor();
        $this->assertTrue($testAuthor == "TestAuthor");
    }

    public function testDate()
    {
        $testDate = $this->ontologyTest->getDate();
        $this->assertTrue($testDate == "01.01.2011");
    }

    public function testAddOntologyFile()
    {

    }

    public function testRemoveOntologyFile()
    {

    }

    public function testGetOntologyFiles()
    {

    }

    public function testUploadFiles()
    {

    }
}
