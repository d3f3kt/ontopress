<?php

namespace OntoPress\Tests\Library;

use OntoPress\Libary\Router;
use OntoPress\Libary\OntoPressTestCase;

class RouterTest extends OntoPressTestCase
{
    private $router;

    public function setUp()
    {
        //$this->router = $this->getMockBuilder('Router')->getMock();
        $this->router = new Router(static::getContainer());
    }
    /*
     * 'OntoPress\Libary\Router',
     * array(),
     * array(static::getContainer())
     * */
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
