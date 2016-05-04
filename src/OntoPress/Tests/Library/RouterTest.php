<?php

namespace OntoPress\Tests\Library;

use OntoPress\Library\Router;
use OntoPress\Library\OntoPressWPTestCase;
use Brain\Monkey\Functions;

/**
 * Class RouterTest
 * Tests the Router Class.
 */
class RouterTest extends OntoPressWPTestCase
{
    /**
     * Router Instance.
     * @var Router
     */
    private $router;

    /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        parent::setUp();
        $this->router = new Router(static::getContainer());
    }

    /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
    public function tearDown()
    {
        unset($this->router);
        parent::tearDown();
    }

    /**
     * Tests generate method.
     */
    public function testGenerate()
    {
        $result = $this->invokeMethod($this->router, 'generate', array("ontopress", array()));
        $this->assertContains("?page=ontopress", $result);
    }

    /**
     * Tests setRoutes method.
     */
    public function testSetRoutes()
    {
        Functions::when('add_menu_page')->justReturn(true);
        Functions::when('add_submenu_page')->justReturn(true);

        $this->router->setRoutes();
    }

    /**
     * Tests controllerCall method.
     */
    public function testControllerCall()
    {
        ob_start();
        $this->router->action_ontopress();
        $this->router->action_ontopress_ontology();
        ob_end_clean();
    }

    /**
     * Tests badMethodException.
     * @expectedException BadMethodCallException
     */
    public function testBadMethodException()
    {
        $this->router->fail_action();
    }

    /**
     * Tests invalidControllerCallException.
     * @expectedException OntoPress\Library\Exception\InvalidControllerCallException
     */
    public function testInvalidControllerCallException()
    {
        $this->invokeMethod($this->router, 'callController', array('InvalidController'));
    }

    /**
     * Tests noActionException.
     * @expectedException OntoPress\Library\Exception\NoActionException
     */
    public function testNoActionException()
    {
        $this->invokeMethod($this->router, 'callController', array('Dashboard:wrongAction'));
    }

    /**
     * Tests noControllerException.
     * @expectedException OntoPress\Library\Exception\NoControllerException
     */
    public function testNoControllerException()
    {
        $this->invokeMethod($this->router, 'callController', array('wrongController:showDashboard'));
    }
}
