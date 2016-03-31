<?php

namespace OntoPress\Tests;

use OntoPress\Controller\FormController;
use OntoPress\Library\OntoPressTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FormControllerTest
 * Creates a FormController and tests it.
 */
class FormControllerTest extends OntoPressTestCase
{
    /**
     * Tests showManageAction function, which should create a rendered twig template about form management.
     */
    public function testShowManageAction()
    {
        $testRequest = new Request();
        $container = static::getContainer();
        $formController = new FormController($container);
        $formOutput = $formController->showManageAction($testRequest);

        $this->assertContains("Formular Verwaltung", $formOutput);
    }

    /**
     * Tests showEditAction function, which should return a rendered twig template about form edits.
     */
    public function testShowEditAction()
    {
        $container = static::getContainer();
        $formController = new FormController($container);
        $formOutput = $formController->showEditAction();

        $this->assertContains("Formular Bearbeiten", $formOutput);
    }

    /**
     * Tests showCreateAction function, which should return a rendered twig template about creating a form.
     */
    public function testShowCreateAction()
    {
        $container = static::getContainer();
        $formController = new FormController($container);
        $formOutput = $formController->showCreateAction();

        $this->assertContains("Formular Anlegen", $formOutput);
    }

    /**
     * Tests showDeleteAction function, which should return a rendered twig template about deleting a form.
     */
    public function testShowDeleteAction()
    {
        $container = static::getContainer();
        $formController = new FormController($container);
        $formOutput = $formController->showDeleteAction();

        $this->assertContains("Formular Löschen", $formOutput);
    }
}
