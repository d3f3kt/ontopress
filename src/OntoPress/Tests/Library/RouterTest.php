<?php

namespace OntoPress\Tests\Library;

use OntoPress\Library\Router;
use OntoPress\Library\OntoPressWPTestCase;
use Brain\Monkey\Functions;

class RouterTest extends OntoPressWPTestCase
{
    private $router;

    public function setUp()
    {
        parent::setUp();
        $this->router = new Router(static::getContainer());
    }

    public function tearDown()
    {
        unset($this->router);
        parent::tearDown();
    }

    public function testGenerate()
    {
        $result = $this->invokeMethod($this->router, 'generate', array("ontopress", array()));
        $this->assertContains("?page=ontopress", $result);
    }

    public function testSetRoutes()
    {
        Functions::when('add_menu_page')->justReturn(true);
        Functions::when('add_submenu_page')->justReturn(true);

        $this->router->setRoutes();
    }

    public function testControllerCall()
    {
        ob_start();
        $this->router->action_ontopress();
        $this->router->action_ontopress_ontology();
        ob_end_clean();
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testBadMethodException()
    {
        $this->router->fail_action();
    }

    /**
     * @expectedException OntoPress\Library\Exception\InvalidControllerCallException
     */
    public function testInvalidControllerCallException()
    {
        $this->invokeMethod($this->router, 'callController', array('InvalidController'));
    }

    /**
     * @expectedException OntoPress\Library\Exception\NoActionException
     */
    public function testNoActionException()
    {
        $this->invokeMethod($this->router, 'callController', array('Dashboard:wrongAction'));
    }

    /**
     * @expectedException OntoPress\Library\Exception\NoControllerException
     */
    public function testNoControllerException()
    {
        $this->invokeMethod($this->router, 'callController', array('wrongController:showDashboard'));
    }
}
