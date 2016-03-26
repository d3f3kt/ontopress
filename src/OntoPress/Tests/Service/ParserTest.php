<?php

namespace OntoPress\Tests;

use Symfony\Component\HttpFoundation\Request;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyFile;
use OntoPress\Service\OntologyParser\Parser;
use OntoPress\Service\OntologyParser\OntologyNode;
use OntoPress\Service\OntologyParser\Restriction;
use OntoPress\Library\OntoPressTestCase;

class ParserTest extends OntoPressTestCase
{
    private $parser;
    private $ontologyNode;
    private $restriction;
    private $dummyChoice;
    
    public function setUp()
    {
        $this->parser = new Parser();
        
        $this->dummyChoice = new OntologyNode('Dummy');
        $this->restriction = new Restriction();
        $this->restriction->addOneOf($this->dummyChoice);
        
        $this->ontologyNode = new OntologyNode(null);
        $this->ontologyNode->setName('TestNode')
            ->setComment('TestComment')
            ->setLabel('TestLabel')
            ->setType('TestType')
            ->setMandatory(true)
            ->setRestriction($this->restriction);
    }
    
    public function tearDown()
    {
        unset($this->parser);
        unset($this->ontologyNode);
        unset($this->restriction);
    }
    
    /**
     * Test Basic setter (addOneOf) and getter.
     */
    public function testRestrictionBasic()
    {
        $this->assertEquals($this->restriction->getOneOf(), array($this->dummyChoice));
    }

    /**
     * Test Basic setter and getter.
     */
    public function testOntologyNodeBasic()
    {
        $this->assertEquals($this->ontologyNode->getName(), 'TestNode');
        $this->assertEquals($this->ontologyNode->getComment(), 'TestComment');
        $this->assertEquals($this->ontologyNode->getLabel(), 'TestLabel');
        $this->assertEquals($this->ontologyNode->getType(), 'TestType');
        $this->assertTrue($this->ontologyNode->getMandatory());
        $this->assertEquals($this->ontologyNode->getRestriction(), $this->restriction);
    }
    
    public function testParsing()
    {
        $ontologyFile = new OntologyFile();
        $ontologyFile->setPath('/../../../Tests/TestFiles/place-ontology.ttl');

        $ontologyObj = new Ontology();
        $ontologyObj->setName("Place")
            ->addOntologyFile($ontologyFile);
        $this->parser->parsing($ontologyObj);
    }
}
