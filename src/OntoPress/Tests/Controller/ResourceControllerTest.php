<?php

namespace OntoPress\Tests;

use OntoPress\Controller\ResourceController;
use OntoPress\Library\OntoPressTestCase;
use Symfony\Component\HttpFoundation\Request;
use Brain\Monkey\Functions;
use Saft\Rdf\NamedNodeImpl;

/**
 * Class ResourceControllerTest
 * Creates a ResourceController and tests it.
 */
class ResourceControllerTest extends OntoPressTestCase
{
    /**
     * @var resourceController
     */
    private $resourceController;

     /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        parent::setUp();
        $this->resourceController = new ResourceController(static::getContainer());
    }

     /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
    public function tearDown()
    {
        unset($this->resourceController);
        parent::tearDown();
    }

    /**
     * Tests showAddAction function, which should return a rendered twig template about adding resources.
     */
    public function testShowAddAction()
    {
        $ontologyEntity = TestHelper::createTestOntology();
        $formEntity = TestHelper::createOntologyForm($ontologyEntity);
        static::getContainer()->get('doctrine')->persist($ontologyEntity);
        static::getContainer()->get('doctrine')->persist($formEntity);
        static::getContainer()->get('doctrine')->flush();

        // test  without valid form
        $this->assertContains("addResourceType", $this->resourceController->showAddAction(new Request()));

        // test with valid form
        $resourceOutputValidForm = $this->resourceController->showAddAction(
            new Request(
                array(),
                array('addResourceType' => array(
                    'form' => '1',
                    'submit' => ''
                )),
                array(),
                array(),
                array(),
                array('REQUEST_METHOD' => 'POST')
            )
        );
        $this->assertContains("window.location", $resourceOutputValidForm);
    }

    /**
     * Tests showAddDetailsAction function, which should return a rendered twig template about adding Details to a resource.
     */
    public function testShowAddDetailsAction()
    {
        $ontologyEntity = TestHelper::createTestOntology();
        $formEntity = TestHelper::createOntologyForm($ontologyEntity);
        static::getContainer()->get('doctrine')->persist($ontologyEntity);
        static::getContainer()->get('doctrine')->persist($formEntity);
        static::getContainer()->get('doctrine')->flush();

        // test without formID
        $resourceNoID = $this->resourceController->showAddDetailsAction(new Request());
        $this->assertContains("window.location", $resourceNoID);

        // test with Id, but no formEntity
        $resourceNoEntity = $this->resourceController->showAddDetailsAction(new Request(array(
            'formId' => 2000
        )));
        $this->assertContains("Formular nicht gefunden!", $resourceNoEntity);

        // formID, formEntity, but no valid form
        $resourceValid = $this->resourceController->showAddDetailsAction(new Request(array(
            'formId' => 1,
            'formEntity' => $formEntity
        )));
        $this->assertContains("Ressourcen hinzufügen", $resourceValid);

        // formID, formEntity, valid form
        // TODO: doesnt work as intended
        $resourceValidForm = $this->resourceController->showAddAction(
            new Request(
                array(),
                array('OntoPressForm' => array(
                    'OntologyField_' => '',
                    'submit' => ''
                )),
                array('formId' => '1'),
                array(),
                array(),
                array('REQUEST_METHOD' => 'POST')
            )
        );
        // $this->assertContains("window.location", $resourceValidForm);
    }

    /**
     * Tests showManagementAction function, which should return a rendered twig template about showing all present resources.
     */
    public function testShowManagementAction()
    {
        // 's' empty, without graph, with order request
        $this->resourceController->showManagementAction(
            new Request(
                array('orderBy' => 'Id')
            )
        );

        // 's' not empty, no graph
        $this->resourceController->showManagementAction(
            new Request(
                array('s' => 'searchString')
            )
        );

        // 's' not empty, with graph
        $this->resourceController->showManagementAction(
            new Request(
                array(
                    's' => 'searchString',
                    'graph' => 'Test:Uri')
            )
        );

        // 's' empty, with graph
        $resourceOutput = $this->resourceController->showManagementAction(
            new Request(
                array(
                    'graph' => 'Test:Uri'
                )
            )
        );
        $this->assertContains("Ressourcen Verwaltung", $resourceOutput);
    }


    /**
     * Tests showDeleteAction function, which should return a rendered twig template about deleting resources.
     */
    public function testShowDeleteAction()
    {
        // no resourceAction
        $result1 = $this->resourceController->showDeleteAction(
            new Request()
        );
        $this->assertContains("window.location", $result1);

        // uri, no valid form
        $result5 = $this->resourceController->showDeleteAction(
            new Request(
                array(
                    'resourceAction' => 'delete',
                    'uri' => 'Test:Uri'
                )
            )
        );
        $this->assertContains("Ressource löschen", $result5);

        // no uri, no valid form
        $result3 = $this->resourceController->showDeleteAction(
            new Request(
                array(
                    'resourceAction' => 'delete'
                )
            )
        );
        $this->assertContains("Ressource löschen", $result3);
    }

    /**
     * Tests showEditAction method.
     */
    public function testShowEditAction()
    {
        // no uri
        $result4 = $this->resourceController->showEditAction(
            new Request()
        );
        $this->assertContains("window.location", $result4);
    }

    /**
     * Tests suspendResourceAction method.
     */
    public function testSuspendResourceAction()
    {
        // uri, non-valid form
        $result2 = $this->resourceController->showDeleteAction(
            new Request(
                array(),
                array(),
                array(
                    'title' => 'uri',
                    'resourceAction' => 'suspend',
                    'uri' => 'Test:Uri'
                )
            )
        );
        $this->assertContains('Ressource deaktivieren', $result2);

        // no uri, non-valid form
        $result4 = $this->resourceController->showDeleteAction(
            new Request(
                array(),
                array(),
                array(
                    'resourceAction' => 'suspend',
                )
            )
        );
        $this->assertContains('Ressource deaktivieren', $result4);
    }

    /**
     * Tests getSearchTable method.
     */
    public function testGetSearchTable()
    {
        $graph = new NamedNodeImpl('test:graph');
        $sparqlManager = $this->invokeMethod($this->resourceController, 'get', array('ontopress.sparql_manager'));

        $this->invokeMethod($this->resourceController, 'getSearchTable', array('searchString'));
        $result = $this->invokeMethod($this->resourceController, 'getSearchTable', array('searchString', $graph));
        $this->assertEquals($sparqlManager->getResourceRowsLike('searchString', $graph), $result);
    }

    /**
     * Tests checkRegex method.
     */
    public function testCheckRegex()
    {
        $this->assertEquals(
            $this->invokeMethod($this->resourceController, 'checkRegex', array('t#ä22st')),
            't22st'
        );
    }
}
