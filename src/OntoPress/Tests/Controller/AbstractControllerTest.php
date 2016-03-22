<?php

namespace OntoPress\Tests;

use OntoPress\Libary\AbstractController;
use OntoPress\Libary\OntoPressTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Definition;
use OntoPress\Entity\Ontology;

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


    public function testTrans()
    {
        $result = $this->invokeMethod($this->abstractController, 'trans', array('idTest1', array(), null, null));
        $this->assertContains("idTest1", $result);
    }

    public function testTransChoice()
    {
        $result = $this->invokeMethod($this->abstractController, 'transChoice', array('idTest2', 0, array(), null, null));
        $this->assertContains("idTest2", $result);
    }

    public function testCreateForm()
    {
        $result = $this->invokeMethod($this->abstractController, 'createForm', array('form', null, array()));
        $this->assertEquals("form", $result->getName());
    }

    public function testGenerateRoute()
    {
        $result = $this->invokeMethod($this->abstractController, 'generateRoute', array('ontopress', array()));
        $this->assertContains("?page=ontopress", $result);
    }
    /*
    public function testGetDoctrine()
    {
        $result = $this->invokeMethod($this->abstractController, 'getDoctrine', array());
        $this->assertFileEquals("doctrine", $result);
    }

    public function testValidate()
    {
        $testVali = new Ontology();
        $errors = $this->invokeMethod($this->abstractController, 'validate', array($testVali, null, false, false));
        $this->assertEquals(0, $errors);
    }
    */
    public function testGet()
    {
        $result = $this->invokeMethod($this->abstractController, 'get', array('service_container'));
        $this->assertEquals(static::getContainer(), $result);
    }
    /*
    public function testGetParameter()
    {
        //$this->abstractController->getContainer() = static::getContainer();
        // needs getContainer, da private
        $this->abstractController->getContainer()->setParameter('test.HelloWorld', 'HelloWorld');
        $result = $this->invokeMethod($this->abstractController, 'getParameter', array('test.HelloWorld'));
        $this->assertEquals('HelloWorld', $result);
    }

    public function testAddFlashMessage()
    {
        //need typ to make a FlashMessage
        $this->invokeMethod($this->abstractController, 'addFlashMessage', array('INFO', 'HelloWorld'));
        $this->assertContains('HelloWorld',
            $this->invokeMethod($this->abstractController, 'get', array('session'))->getFlashBag());
    }
    */
}
