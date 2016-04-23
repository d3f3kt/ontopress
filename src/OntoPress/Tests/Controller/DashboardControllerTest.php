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
     * @var DashboardController
     */
    private $dashboardController;

    public function setUp()
    {
        parent::setUp();
        $this->dashboardController = new DashboardController(static::getContainer());
    }

    public function tearDown()
    {
        unset($this->dashboardController);
        parent::tearDown();
    }

    /**
     * Tests showDashboardAction, which should return a rendered twig template for the dashboard.
     */
    public function testDashboardController()
    {
        $dashboardOutput = $this->dashboardController->showDashboardAction();
        $this->assertContains("Dashboard", $dashboardOutput);
    }
}
