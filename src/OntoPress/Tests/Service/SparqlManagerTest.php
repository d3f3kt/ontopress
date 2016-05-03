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
     * Graph of a statement.
     * @var String
     */
    private $graph;

    /**
     * Subject of a statement.
     * @var String
     */
    private $subject;

    /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        parent::setUp();
        $this->store = static::getContainer()->get('saft.store');
        $this->sparqlManager = static::getContainer()->get('ontopress.sparql_manager');

        $this->graph = new NamedNodeImpl('test:graph');
        $this->subject = new NamedNodeImpl('test:subject');
        $predicate = new NamedNodeImpl('OntoPress:author');
        $nodeFactory = new NodeFactoryImpl();
        $object = $nodeFactory->createLiteral('TestAuthor');
        $statements = array(
            new StatementImpl($this->subject, $predicate, $object),
            new StatementImpl($this->subject, $this->subject, $object)
        );
        // $this->store->dropGraph($this->graph);
        $this->store->createGraph($this->graph);
        $this->store->addStatements($statements, $this->graph);
    }

    /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
    public function tearDown()
    {
        unset($this->graph);
        unset($this->subject);
        unset($this->sparqlManager);
        unset($this->store);
        parent::tearDown();
    }

    /**
     * Test getAllTriples
     */
    public function testGetAllTriples()
    {

        $triples1 = $this->sparqlManager->getAllTriples($this->graph);
        $triples2 = $this->sparqlManager->getAllTriples();
        $this->assertEquals($triples1, $triples2);
    }

    /**
     * Test getAllManageRows
     */
    public function testGetAllManageRows()
    {
        $predicateA = new NamedNodeImpl('OntoPress:author');
        $predicateT = new NamedNodeImpl('OntoPress:name');
        $predicateD = new NamedNodeImpl('OntoPress:date');
        $nodeFactory = new NodeFactoryImpl();
        $objectT = $nodeFactory->createLiteral('TestTitle');
        $objectA = $nodeFactory->createLiteral('TestAuthor');
        $objectD = $nodeFactory->createLiteral('TestDate');
        $statements = array(
            new StatementImpl($this->subject, $predicateT, $objectT),
            new StatementImpl($this->subject, $predicateA, $objectA),
            new StatementImpl($this->subject, $predicateD, $objectD),
        );

        $this->store->dropGraph($this->graph);
        $this->store->createGraph($this->graph);
        $this->store->addStatements($statements, $this->graph);

        $expectedRow = array(
            'title' => 'TestTitle',
            'author' => 'TestAuthor',
            'date' => 'TestDate'
        );
        $rows = $this->sparqlManager->getAllManageRows($this->graph);
        $this->assertContains($expectedRow, $rows);
    }

    /**
     * Test getAllManageRows
     */
    public function testGetLatestResources()
    {
        $graph = new NamedNodeImpl('test:graph');
        $subjectOld = new NamedNodeImpl('test:subjectOld');
        $subjectNew = new NamedNodeImpl('test:subjectNew');
        $predicateA = new NamedNodeImpl('OntoPress:author');
        $predicateT = new NamedNodeImpl('OntoPress:name');
        $predicateD = new NamedNodeImpl('OntoPress:date');
        $nodeFactory = new NodeFactoryImpl();
        $objectOldT = $nodeFactory->createLiteral('TestTitleOld');
        $objectOldA = $nodeFactory->createLiteral('TestAuthorOld');
        $objectOldD = $nodeFactory->createLiteral('A');
        $statementsOld = array(
            new StatementImpl($subjectOld, $predicateT, $objectOldT),
            new StatementImpl($subjectOld, $predicateA, $objectOldA),
            new StatementImpl($subjectOld, $predicateD, $objectOldD),
        );
        $objectNewT = $nodeFactory->createLiteral('TestTitleNew');
        $objectNewA = $nodeFactory->createLiteral('TestAuthorNew');
        $objectNewD = $nodeFactory->createLiteral('B');
        $statementsNew = array(
            new StatementImpl($subjectNew, $predicateT, $objectNewT),
            new StatementImpl($subjectNew, $predicateA, $objectNewA),
            new StatementImpl($subjectNew, $predicateD, $objectNewD),
        );

        $this->store->dropGraph($graph);
        $this->store->createGraph($graph);
        $this->store->addStatements($statementsOld, $graph);
        $this->store->addStatements($statementsNew, $graph);

        $expectedRow = array( 'title' => 'TestTitleNew', 'author' => 'TestAuthorNew', 'date' => 'B');
        $rows = $this->sparqlManager->getLatestResources($graph);
        $this->assertContains($expectedRow, $rows);
    }

    /**
     * Test getResourceTriples
     */
    public function testGetResourceTriples()
    {
        $triple = $this->sparqlManager->getResourceTriples($this->subject, $this->graph);
        $this->assertNotEmpty($triple);
    }

    /**
     * Test countResources
     */
    public function testCountResources()
    {
        $count = $this->sparqlManager->countResources($this->graph);
        $this->assertEquals(1, $count);
    }

    public function testGetSortedTable()
    {
        $result = $this->sparqlManager->getSortedTable('author', 'ASC');
        $this->assertEquals(array('subject'=>array('author'=>'TestAuthor')), $result);
    }

    public function testGetStringFromUri()
    {
        $resultString = $this->invokeMethod($this->sparqlManager, 'getStringFromUri', array('test:testUri'));
        $this->assertEquals('test Uri', $resultString);
    }

    /*
    public function testGetFormId()
    {
        $result = $this->sparqlManager->getFormId('test:subject');
        $this->assertEquals($this->subject, $result);
    }
    */

    // TODO: exportRDF, deleteResources, countTriples
}
