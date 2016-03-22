<?php

namespace OntoPress\Tests\Library;

use OntoPress\Library\Router;
use OntoPress\Library\OntoPressTestCase;

class RouterTest extends OntoPressTestCase
{
    private $router;

    public function setUp()
    {
        $this->router = new Router(static::getContainer());
    }

    public function tearDown()
    {
        unset($this->router);
    }

    public function testGenerate()
    {
        $result = $this->invokeMethod($this->router, 'generate', array("ontopress", array()));
        $this->assertContains("?page=ontopress", $result);
    }
}
