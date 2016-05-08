<?php

namespace OntoPress\Tests;

use OntoPress\Controller\ResourceController;
use OntoPress\Entity\Ontology;
use OntoPress\Library\OntoPressTestCase;
use OntoPress\Tests\Entity\OntologyTest;
use OntoPress\Service\SparqlManager;
use Symfony\Component\HttpFoundation\Request;
use Brain\Monkey\Functions;
use OntoPress\Tests\TestHelper;

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
        Functions::when('wp_get_current_user')->alias(array('OntoPress\Tests\TestHelper', 'emulateWPUser'));

        // test without ID
        $resourceNoID = $this->resourceController->showAddDetailsAction(new Request());
        $this->assertContains("window.location", $resourceNoID);

        // test with Id, but no formEntity
        $resourceNoEntity = $this->resourceController->showAddDetailsAction(new Request(array(
            'formId' => 2000
        )));
        $this->assertContains("Formular nicht gefunden!", $resourceNoEntity);

        // valid resource, but no valid form
        $resourceValid = $this->resourceController->showAddDetailsAction(new Request(array(
            'formId' => 1,
            'formEntity' => $formEntity
        )));
        $this->assertContains("Ressourcen Hinzufügen", $resourceValid);

        // valid resource, valid form
        $resourceValidForm = $this->resourceController->showAddAction(
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
        $this->assertContains("window.location", $resourceValidForm);
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
        $ontologyEntity = TestHelper::createTestOntology();
        $formEntity = TestHelper::createOntologyForm($ontologyEntity);
        static::getContainer()->get('doctrine')->persist($ontologyEntity);
        static::getContainer()->get('doctrine')->persist($formEntity);
        static::getContainer()->get('doctrine')->flush();

        // uri, no valid form
        $result1 = $this->resourceController->showDeleteAction(
            new Request(
                array(
                    'uri' => 'Test:Uri'
                )
            )
        );
        $this->assertContains("Ressource löschen", $result1);

        // uri, valid form
        $result2 = $this->resourceController->showDeleteAction(
            new Request(
                array(),
                array(
                    'resourceDeleteType' => array(
                        'submit' => ''
                )),
                array(
                    'uri' => 'Test:UriDelete'
                ),
                array(),
                array(),
                array('REQUEST_METHOD' => 'POST')
            )
        );
        $this->assertContains('window.location', $result2);

        // no uri, no valid form
        $result3 = $this->resourceController->showDeleteAction(
            new Request()
        );
        $this->assertContains("Ressource löschen", $result3);

        // no uri, valid form
        $result4 = $this->resourceController->showDeleteAction(
            new Request(
                array(),
                array(
                    'resourceDeleteType' => array(
                        'submit' => ''
                    )),
                array(
                    'resources' => ''
                ),
                array(),
                array(),
                array('REQUEST_METHOD' => 'POST')
            )
        );
        $this->assertContains("Ressource löschen", $result4);
        
    }

    public function testShowEditAction()
    {
        $ontologyEntity = TestHelper::createTestOntology();
        $formEntity = TestHelper::createOntologyForm($ontologyEntity);
        static::getContainer()->get('doctrine')->persist($ontologyEntity);
        static::getContainer()->get('doctrine')->persist($formEntity);
        static::getContainer()->get('doctrine')->flush();
        /*
        // uri, no Respository form
        $result4 = $this->resourceController->showDeleteAction(
            new Request(
                array(),
                array(
                    '' => array(
                        'submit' => ''
                    )),
                array(
                    'uri' => 'Test:UriDelete'
                ),
                array(),
                array(),
                array('REQUEST_METHOD' => 'POST')
            )
        );
        $this->assertContains("window.location", $result4);
        */
        // uri, repository form, valid form

        // uri, repository form, no valid form

        // no uri
    }
}
