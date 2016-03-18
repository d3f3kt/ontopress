<?php

namespace OntoPress\Tests;

use OntoPress\Libary\AbstractController;
use OntoPress\Libary\OntoPressTestCase;
use Symfony\Component\DependencyInjection\Container;

class AbstractControllerTest extends OntoPressTestCase
{
    /**
     * AbstractController stub
     * @var AbstractController
     */
    private $abstractController;

    public function setUp()
    {
        $this->abstractController = $this->getMockForAbstractClass(
            'OntoPress\Libary\AbstractController',
            array(static::getContainer())
        );
    }

    public function tearDown()
    {
        unset($this->abstractController);
    }

    public function testRender()
    {
        $result = $this->invokeMethod($this->abstractController, 'render', array('base.html.twig', array()));
        $this->assertContains("wrap ontopressWrap", $result);
    }

    /*
    public function testTrans()
    {

    }

    public function testTransChoice()
    {

    }
    */
}
