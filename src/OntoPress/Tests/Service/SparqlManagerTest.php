<?php

namespace OntoPress\Tests;

use OntoPress\Library\OntoPressTestCase;
use OntoPress\Service\SparqlManager;
use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\NamedNodeImpl;
use Saft\Rdf\StatementImpl;
use Saft\Store\Store;

class SparqlManagerTest extends OntoPressTestCase
{
    /**
     * SparqlManager Instance.
     * @var SparqlManager
     */
    private $sparqlManager;

    /**
     * ARC2 Instance.
     * @var Store;
     */
    private $store;

    /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        parent::setUp();
        
        $this->store = static::getContainer()->get('saft.store');
        $this->sparqlManager = static::getContainer()->get('ontopress.sparql_manager');
    }

    /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
    public function tearDown()
    {
        unset($this->sparqlManager);
        unset($this->store);
        parent::tearDown();
    }

    /**
     * Test getAllTriples
     */
    public function testGetAllTriples()
    {
        $graph = new NamedNodeImpl('test:graph');
        $subject = new NamedNodeImpl('test:subject');
        $predicate = new NamedNodeImpl('test:predicate');
        $nodeFactory = new NodeFactoryImpl();
        $object = $nodeFactory->createLiteral('TestObject');
        $statements = array(
            new StatementImpl($subject, $predicate, $object),
            new StatementImpl($predicate, $predicate, $object)
        );

        $this->store->dropGraph($graph);
        $this->store->createGraph($graph);
        $this->store->addStatements($statements, $graph);

        $triples1 = $this->sparqlManager->getAllTriples($graph);
        $triples2 = $this->sparqlManager->getAllTriples();
        $this->assertEquals($triples1, $triples2);
    }

    /**
     * Test getAllManageRows
     */
    public function testGetAllManageRows()
    {
        $graph = new NamedNodeImpl('test:graph');
        $subject = new NamedNodeImpl('test:subject');
        $predicate = new NamedNodeImpl('OntoPress:author');
        $nodeFactory = new NodeFactoryImpl();
        $object = $nodeFactory->createLiteral('TestAuthor');
        $statements = array(
            new StatementImpl($subject, $predicate, $object),
        );

        $this->store->dropGraph($graph);
        $this->store->createGraph($graph);
        $this->store->addStatements($statements, $graph);

        $expectedRow = array(
            'author' => 'TestAuthor'
        );
        $rows = $this->sparqlManager->getAllManageRows($graph);

        $this->assertContains($expectedRow, $rows);
    }

    /**
     * Test getResourceTriples
     */
    public function testGetResourceTriples()
    {
        $graph = new NamedNodeImpl('test:graph');
        $subject = new NamedNodeImpl('test:subject');
        $predicate = new NamedNodeImpl('OntoPress:author');
        $nodeFactory = new NodeFactoryImpl();
        $object = $nodeFactory->createLiteral('TestAuthor');
        $statements = array(
            new StatementImpl($subject, $predicate, $object),
            new StatementImpl($subject, $subject, $object)
        );
        $this->store->dropGraph($graph);
        $this->store->createGraph($graph);
        $this->store->addStatements($statements, $graph);
        
        $triple = $this->sparqlManager->getResourceTriples($subject, $graph);
        
        $this->assertNotEmpty($triple);
    }
    
    public function testCountResources()
    {
        $graph = new NamedNodeImpl('test:graph');
        $subject = new NamedNodeImpl('test:subject');
        $predicate = new NamedNodeImpl('OntoPress:author');
        $nodeFactory = new NodeFactoryImpl();
        $object = $nodeFactory->createLiteral('TestAuthor');
        $statements = array(
            new StatementImpl($subject, $predicate, $object),
            new StatementImpl($subject, $subject, $object)
        );
        $this->store->dropGraph($graph);
        $this->store->createGraph($graph);
        $this->store->addStatements($statements, $graph);

        $count = $this->sparqlManager->countResources($graph);

        $this->assertEquals(1, $count);
    }
}
