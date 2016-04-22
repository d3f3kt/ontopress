<?php

namespace OntoPress\Tests;

use OntoPress\Controller\ResourceController;
use OntoPress\Library\OntoPressTestCase;
use Symfony\Component\HttpFoundation\Request;
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
        $this->resourceController = new ResourceController(static::getContainer());
    }

    public function tearDown()
    {
        unset($this->resourceController);
    }

    /**
     * Tests showAddAction function, which should return a rendered twig template about adding resources.
     */
    public function testShowAddAction()
    {
        $resourceOutput = $this->resourceController->showAddAction(new Request());
        $this->assertContains("Ressourcen Hinzufügen", $resourceOutput);
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

        $this->assertContains("window.location", $resourceNoID);
        $this->assertContains("Formular nicht gefunden!", $resourceNoEntity);
        $this->assertContains("Ressourcen Hinzufügen", $resourceValid);
    }

    /**
     * Tests showManagementAction function, which should return a rendered twig template about showing all present resources.
     */
    public function testShowManagementAction()
    {
        $resourceOutput = $this->resourceController->showManagementAction();
        $this->assertContains("Ressourcen Verwaltung", $resourceOutput);
    }

    /**
     * Tests showDeleteAction function, which should return a rendered twig template about deleting resources.
     */
    public function testShowDeleteAction()
    {
        $resourceOutput = $this->resourceController->showDeleteAction();
        $this->assertContains("Ressource Löschen", $resourceOutput);
    }
}
