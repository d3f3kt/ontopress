<?php

namespace OntoPress\Tests;

use OntoPress\Controller\OntologyController;
use OntoPress\Libary\OntoPressTestCase;

class OntologyControllerTest extends OntoPressTestCase
{
    public function testShowManageAction()
    {
        $container = static::getContainer();
        $ontologyController = new OntologyController($container);
        $ontologyOutput = $ontologyController->showManageAction();

        $this->assertContains("Ontologie Verwaltung", $ontologyOutput);
    }

    public function testShowDeleteAction()
    {
        $container = static::getContainer();
        $ontologyController = new OntologyController($container);
        $ontologyOutput = $ontologyController->showDeleteAction();

        $this->assertContains("Ontologie Löschen", $ontologyOutput);
    }

    public function testShowAddAction()
    {
        $container = static::getContainer();
        $ontologyController = new OntologyController($container);
        $ontologyOutput = $ontologyController->showAddAction();

        $this->assertContains("Ontologie hochladen", $ontologyOutput);
    }
}
