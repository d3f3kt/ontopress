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

    private $resourceController;

    public function setUp()
    {
        parent::setUp();
        $this->resourceController = new ResourceController(static::getContainer());
    }

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

        $this->assertContains("addResourceType", $this->resourceController->showAddAction(new Request()));
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


        $resourceNoID = $this->resourceController->showAddDetailsAction(new Request());
        $resourceNoEntity = $this->resourceController->showAddDetailsAction(new Request(array(
            'formId' => 2000
        )));
        $resourceValid = $this->resourceController->showAddDetailsAction(new Request(array(
            'formId' => 2,
            'formEntity' => $formEntity
        )));
        /*
        $resourceValidForm = $this->resourceController->showAddDetailsAction(new Request(

        ));
        $this->assertContains("window.location", $resourceValidForm);
        */
        $this->assertContains("window.location", $resourceNoID);
        $this->assertContains("Formular nicht gefunden!", $resourceNoEntity);
        $this->assertContains("Ressourcen Hinzufügen", $resourceValid);
    }

    /**
     * Tests showManagementAction function, which should return a rendered twig template about showing all present resources.
     */
    public function testShowManagementAction()
    {
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
        $resourceOutput = $this->resourceController->showDeleteAction(
            new Request(
                array(
                    'uri' => 'Test:Uri'
                )
            )
        );
        $this->assertContains("Ressource Löschen", $resourceOutput);
    }
}
