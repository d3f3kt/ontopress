<?php

namespace OntoPress\Tests;

use OntoPress\Controller\DashboardController;
use OntoPress\Library\OntoPressTestCase;

class DashboardControllerTest extends OntoPressTestCase
{
    public function testDashboardController()
    {
        $container = static::getContainer();
        $dashboardController = new DashboardController($container);
        $dashboardOutput = $dashboardController->showDashboardAction();

        $this->assertContains("wrap ontopressWrap", $dashboardOutput);
    }
}
