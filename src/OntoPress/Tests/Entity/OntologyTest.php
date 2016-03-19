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
        $this->ontologyTest->setName("TestOntology");
        $this->ontologyTest->setAuthor("TestAuthor");
        $date = date_create('2011-01-01');
        $this->ontologyTest->setDate($date);
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
/*
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
*/
}
