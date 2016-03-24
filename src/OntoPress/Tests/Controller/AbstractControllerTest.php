<?php

namespace OntoPress\Tests;

use OntoPress\Library\AbstractController;
use OntoPress\Library\OntoPressTestCase;
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
            'OntoPress\Library\AbstractController',
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

    public function testGetDoctrine()
    {
        $result = $this->invokeMethod($this->abstractController, 'getDoctrine', array());
        $this->assertInstanceOf("Doctrine\ORM\EntityManager", $result);
    }

    public function testValidate()
    {
        $testVali = new Ontology();
        $errors = $this->invokeMethod($this->abstractController, 'validate', array($testVali));
        $this->assertGreaterThan(0, $errors->count());
    }

    public function testGet()
    {
        $result = $this->invokeMethod($this->abstractController, 'get', array('service_container'));
        $this->assertEquals(static::getContainer(), $result);
    }

    public function testGetParameter()
    {
        $result = $this->invokeMethod($this->abstractController, 'getParameter', array('ontopress.root_dir'));
        $this->assertContains($result, __DIR__);
    }

    public function testAddFlashMessage()
    {
        $this->invokeMethod($this->abstractController, 'addFlashMessage', array('info', 'HelloWorld'));
        $this->assertContains('HelloWorld', static::getContainer()->get('session')->getFlashBag()->get('info'));
    }
}
