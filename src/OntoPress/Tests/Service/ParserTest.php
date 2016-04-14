<?php

namespace OntoPress\Tests;

use Symfony\Component\HttpFoundation\Request;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyFile;
use OntoPress\Service\OntologyParser;
use OntoPress\Library\OntoPressTestCase;

class ParserTest extends OntoPressTestCase
{
    private $parser;

    public function setUp()
    {
        $this->parser = static::getContainer()->get('ontopress.ontology_parser');
    }

    public function tearDown()
    {
        unset($this->parser);
        //unset($this->ontologyNode);
        //unset($this->restriction);
    }

    /**
     * Test Parsing-method
     */
    public function testParsing()
    {
        $ontologyFile = new OntologyFile();
        $ontologyFile->setPath('/../../../Tests/TestFiles/place-ontology.ttl');

        $ontologyObj = new Ontology();
        $ontologyObj->setName("Place")
            ->addOntologyFile($ontologyFile);
        $this->parser->parsing($ontologyObj);
    }

    /**
     * Test groupOntologies method, which should return a string identical to the given one, except cut off at the last '/'
     */
    public function testGroupOntologies()
    {
        $this->assertEquals($this->parser->groupOntologies("test/testing/rootzone"), "test/testing");
        $this->assertEquals($this->parser->groupOntologies(""), "");
    }

    /**
     * Test writeDataHandler method, which should save OntologyNodes in the database, based on the Ontology they come from
     */
    public function testWriteDataHandler()
    {
        // maybe needed:
        // getConnection()
        // getDataSet()
    }
}
