<?php

namespace OntoPress\Tests;

use OntoPress\Controller\ResourceController;
use OntoPress\Library\OntoPressTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ResourceControllerTest
 * Creates a ResourceController and tests it.
 */
class ResourceControllerTest extends OntoPressTestCase
{
    /**
     * Tests showAddAction function, which should return a rendered twig template about adding resources.
     */
    public function testShowAddAction()
    {
        $container = static::getContainer();
        $resourceController = new ResourceController($container);
        $resourceOutput = $resourceController->showAddAction(new Request());

        $this->assertContains("Ressourcen Hinzufügen", $resourceOutput);
    }

    /**
     * Tests showAddDetailsAction function, which should return a rendered twig template about adding Details to a resource.
     */
    public function testShowAddDetailsAction()
    {
        /*
        $container = static::getContainer();
        $resourceController = new ResourceController($container);
        $resourceOutput = $resourceController->showAddDetailsAction(new Request());

        $this->assertContains("Ressourcen Hinzufügen", $resourceOutput);
        */
    }

    /**
     * Tests showManagementAction function, which should return a rendered twig template about showing all present resources.
     */
    public function testShowManagementAction()
    {
        $container = static::getContainer();
        $resourceController = new ResourceController($container);
        $resourceOutput = $resourceController->showManagementAction();

        $this->assertContains("Ressourcen Verwaltung", $resourceOutput);
    }

    /**
     * Tests showDeleteAction function, which should return a rendered twig template about deleting resources.
     */
    public function testShowDeleteAction()
    {
        $container = static::getContainer();
        $resourceController = new ResourceController($container);
        $resourceOutput = $resourceController->showDeleteAction();

        $this->assertContains("Ressource Löschen", $resourceOutput);
    }
}
