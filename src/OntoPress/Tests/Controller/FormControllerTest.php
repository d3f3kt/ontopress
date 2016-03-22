<?php

namespace OntoPress\Tests;

use OntoPress\Controller\FormController;
use OntoPress\Library\OntoPressTestCase;

class FormControllerTest extends OntoPressTestCase
{
    public function testShowManageAction()
    {
        $container = static::getContainer();
        $formController = new FormController($container);
        $formOutput = $formController->showManageAction();

        $this->assertContains("Formular Verwaltung", $formOutput);
    }

    public function testShowEditAction()
    {
        $container = static::getContainer();
        $formController = new FormController($container);
        $formOutput = $formController->showEditAction();

        $this->assertContains("Formular Bearbeiten", $formOutput);
    }

    public function testShowCreateAction()
    {
        $container = static::getContainer();
        $formController = new FormController($container);
        $formOutput = $formController->showCreateAction();

        $this->assertContains("Formular Anlegen", $formOutput);
    }

    public function testShowDeleteAction()
    {
        $container = static::getContainer();
        $formController = new FormController($container);
        $formOutput = $formController->showDeleteAction();

        $this->assertContains("Formular Löschen", $formOutput);
    }
}
