<?php

namespace OntoPress\Tests;

use OntoPress\Entity\OntologyField;
use Symfony\Component\HttpFoundation\Request;
use OntoPress\Entity\OntologyFile;
use OntoPress\Service\OntologyParser;
use OntoPress\Library\OntoPressTestCase;
use OntoPress\Entity\Restriction;

/**
 * Class ParserTest
 * Tests the parser.
 */
class ParserTest extends OntoPressTestCase
{
    /**
     * OntologyParser Entity.
     * @var OntologyParser
     */
    private $parser;

    /**
     * Test setUp.
     * Get called before every test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->parser = static::getContainer()->get('ontopress.ontology_parser');
    }

    /**
     * Test tearDown.
     * Gets called after every test.
     */
    public function tearDown()
    {
        unset($this->parser);
        parent::tearDown();
    }

    /**
     * Tests Parsing-method.
     */
    public function testParsing()
    {
        $ontology = TestHelper::createTestOntology(false);
        $ontologyFile = new OntologyFile();
        $ontologyFile->setPath('/../../../Tests/TestFiles/place-ontology.ttl');
        $ontology->addOntologyFile($ontologyFile);

        $this->parser->parsing($ontology);
    }

    /**
     * Tests groupOntologies method, which should return a string identical to the given one, except cut off at the last '/'
     */
    public function testGroupOntologies()
    {
        $this->assertEquals($this->parser->groupOntologies("test/testing/rootzone"), "test/testing");
        $this->assertEquals($this->parser->groupOntologies(""), "");
    }
    
    /**
     * Tests selectHandler method, which sets an objects type to TYPE_SELECT, if its has more then 2 Restrictions.
     */
    public function testSelectHandler()
    {
        $objectLess = new OntologyField();
        $objectLessArray[] = $objectLess;
        $this->parser->selectHandler($objectLessArray);
        $this->assertNull($objectLessArray[0]->getType());

        $objectMore = new OntologyField();
        $objectMore
            ->addRestriction(new Restriction())
            ->addRestriction(new Restriction())
            ->addRestriction(new Restriction())
            ->addRestriction(new Restriction())
            ->addRestriction(new Restriction());
        $objectMoreArray[] = $objectMore;
        $this->parser->selectHandler($objectMoreArray);
        $this->assertEquals($objectMoreArray[0]->getType(), OntologyField::TYPE_SELECT);

    }
}
