<?php

namespace OntoPress\Tests;

use OntoPress\Controller\ResourceController;
use OntoPress\Library\OntoPressTestCase;

class ResourceControllerTest extends OntoPressTestCase
{
    public function testShowAddAction()
    {
        $container = static::getContainer();
        $resourceController = new ResourceController($container);
        $resourceOutput = $resourceController->showAddAction();

        $this->assertContains("Ressourcen Hinzufügen", $resourceOutput);
    }

    public function testShowAddDetailsAction()
    {
        $container = static::getContainer();
        $resourceController = new ResourceController($container);
        $resourceOutput = $resourceController->showAddDetailsAction();

        $this->assertContains("Ressourcen Hinzufügen", $resourceOutput);
    }

    public function testShowManagementAction()
    {
        $container = static::getContainer();
        $resourceController = new ResourceController($container);
        $resourceOutput = $resourceController->showManagementAction();

        $this->assertContains("Ressourcen Verwaltung", $resourceOutput);
    }

    public function testShowDeleteAction()
    {
        $container = static::getContainer();
        $resourceController = new ResourceController($container);
        $resourceOutput = $resourceController->showDeleteAction();

        $this->assertContains("Ressource Löschen", $resourceOutput);
    }
}
