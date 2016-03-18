<?php

namespace OntoPress\Tests;

use OntoPress\Libary\AbstractController;
use OntoPress\Libary\OntoPressTestCase;

class AbstractControllerTest extends OntoPressTestCase
{
    public function testRender()
    {
        /*
        $abstMock = $this->getMockForAbstractClass(
            "AbstractController",
            array(), //array($container),
            "",
            true,
            true,
            true,
            array()
        );
        */

        //$container = static::getContainer();
        $mock =  $this->getMock(
            "AbstractController",
            array(),
            array(), //array($container),
            "",
            true,
            true,
            true
        );

        $result = $mock->render('base.html.twig', array());
        //$this->assertContains("wrap ontopressWrap", $result);
        unset($this->mock);
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
