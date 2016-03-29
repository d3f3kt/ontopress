<?php

namespace OntoPress\Tests;

use OntoPress\Controller\DashboardController;
use OntoPress\Library\OntoPressTestCase;

/**
 * Class DashboardControllerTest
 * Creates a DashboardController and tests it.
 */
class DashboardControllerTest extends OntoPressTestCase
{
    /**
     * Tests showDashboardAction, which should return a rendered twig template for the dashboard.
     */
    public function testDashboardController()
    {
        $container = static::getContainer();
        $dashboardController = new DashboardController($container);
        $dashboardOutput = $dashboardController->showDashboardAction();

        $this->assertContains("wrap ontopressWrap", $dashboardOutput);
    }
}
