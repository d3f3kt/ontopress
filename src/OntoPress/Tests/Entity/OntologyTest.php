<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyFile;
use OntoPress\Libary\OntoPressTestCase;

class OntologyTest extends OntoPressTestCase
{
    public $ontologyTest;
    public $ontologyFileTest;

    public function setUp()
    {
        $this->ontologyTest = new Ontology();
        $this->ontologyTest->setName("TestOntology");
        $this->ontologyTest->setAuthor("TestAuthor");
        $date = date_create('2011-01-01');
        $this->ontologyTest->setDate($date);
        $this->ontologyFileTest = new OntologyFile();
        $this->ontologyFileTest->setPath("TestPath");
        $this->ontologyFileTest->setOntology(null);
        $this->ontologyFileTest->setFile(null);
    }

    public function testName()
    {
        $testOntology = $this->ontologyTest->getName();
        $this->assertEquals($testOntology, "TestOntology");
    }

    public function testAuthor()
    {
        $testAuthor = $this->ontologyTest->getAuthor();
        $this->assertEquals($testAuthor, "TestAuthor");
    }

    public function testDate()
    {
        $testDate = $this->ontologyTest->getDate();
        $this->assertEquals($testDate, $date = date_create('2011-01-01'));
    }

    public function testAddOntologyFile()
    {
        $this->ontologyTest->addOntologyFile($this->ontologyFileTest);
        $this->assertContains($this->ontologyFileTest, $this->ontologyTest->getOntologyFiles());
    }

/*    public function testGetOntologyFiles()
    {
        $this->assertEquals($this->ontologyFileTest, $this->ontologyTest->getOntologyFiles());
    }
*/
    public function testRemoveOntologyFile()
    {
        $this->ontologyTest->addOntologyFile($this->ontologyFileTest);
        $this->ontologyTest->removeOntologyFile($this->ontologyFileTest);
        // assert ... ?
    }
/*
    public function testUploadFiles()
    {

    }
*/
}
