<?php

namespace OntoPress\Tests;

use OntoPress\Controller\DashboardController;

class DashboardControllerTest extends PHPUnit_Framework_TestCase{
    public function testDashboardController()
    {
        $dashboardcontroller = new DashboardController();
        if (strpos($dashboardcontroller->showDashboardController(), 'wrap ontopressWrap') !== false) {
            return true;
        }
        else {
            return false;
        }
    }
}
