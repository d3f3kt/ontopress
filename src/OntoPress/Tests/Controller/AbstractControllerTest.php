<?php

namespace OntoPress\Tests;

use OntoPress\Library\AbstractController;
use OntoPress\Library\OntoPressTestCase;
use OntoPress\Entity\Ontology;

/**
 * Class AbstractControllerTest
 * Creates a mock of the AbstractController class and tests it.
 */
class AbstractControllerTest extends OntoPressTestCase
{
    /**
     * AbstractController stub
     * @var AbstractController
     */
    private $abstractController;

    /**
     * AbstractController Mock creation;
     * does run before every test.
     */
    public function setUp()
    {
        $this->abstractController = $this->getMockForAbstractClass(
            'OntoPress\Library\AbstractController',
            array(static::getContainer())
        );
    }

    /**
     * AbstractController Mock unset;
     * does run after every test.
     */
    public function tearDown()
    {
        unset($this->abstractController);
    }

    /**
     * Tests render function, which should create HTML strings from Twig-form.
     * Depends on a twig form to work, i.e. "base.html.twig".
     */
    public function testRender()
    {
        $result = $this->invokeMethod($this->abstractController, 'render', array('base.html.twig', array()));
        $this->assertContains("wrap ontopressWrap", $result);
    }

    /**
     * Tests trans function, which should translate a message to a string.
     * Depends on a given id of the message, a parameter list and domain or local information (standart null).
     */
    public function testTrans()
    {
        $result = $this->invokeMethod($this->abstractController, 'trans', array('idTest1', array(), null, null));
        $this->assertContains("idTest1", $result);
    }

    /**
     * Tests transChoice function, which should translate a choice message to a string.
     * Depends on a given id of the message, the choice number, a parameter list and domain or local information (standart null).
     */
    public function testTransChoice()
    {
        $result = $this->invokeMethod($this->abstractController, 'transChoice', array('idTest2', 0, array(), null, null));
        $this->assertContains("idTest2", $result);
    }

    /**
     * Tests createForm function, which should create a form.
     * Depends on type, initial data and an array of options.
     */
    public function testCreateForm()
    {
        $result = $this->invokeMethod($this->abstractController, 'createForm', array('form', null, array()));
        $this->assertEquals("form", $result->getName());
    }

    /**
     * Tests generateRoute function, which should return the fitting url based on the sitename.
     * Depends on a string of the sitename and a array of parameters.
     */
    public function testGenerateRoute()
    {
        $result = $this->invokeMethod($this->abstractController, 'generateRoute', array('ontopress', array()));
        $this->assertContains("?page=ontopress", $result);
    }

    /**
     * Tests getDoctrine function, which should return an Instance of a Doctrine.
     */
    public function testGetDoctrine()
    {
        $result = $this->invokeMethod($this->abstractController, 'getDoctrine', array());
        $this->assertInstanceOf("Doctrine\ORM\EntityManager", $result);
    }

    /**
     * Tests validate function, which should give an array of errors validating an Entity.
     * Depends on an Entity (here an Ontology) to test.
     */
    public function testValidate()
    {
        $testVali = new Ontology();
        $errors = $this->invokeMethod($this->abstractController, 'validate', array($testVali));
        $this->assertGreaterThan(0, $errors->count());
    }

    /**
     * Tests get function, which should return something given the name.
     * Depends on a string name.
     */
    public function testGet()
    {
        $result = $this->invokeMethod($this->abstractController, 'get', array('service_container'));
        $this->assertEquals(static::getContainer(), $result);
    }

    /**
     * Tests getParameter function, which should a string of the paramters, that the container of the controller has.
     * Depends on the name of the wanted parameter.
     */
    public function testGetParameter()
    {
        $result = $this->invokeMethod($this->abstractController, 'getParameter', array('ontopress.root_dir'));
        $this->assertContains($result, __DIR__);
    }

    /**
     * Tests addFlashMessage function, which should add a FlashMessage to the flashBag, so it can be displayed.
     * Depends on a type of which the Message will be and a string of what it will contain.
     */
    public function testAddFlashMessage()
    {
        $this->invokeMethod($this->abstractController, 'addFlashMessage', array('info', 'HelloWorld'));
        $this->assertContains('HelloWorld', static::getContainer()->get('session')->getFlashBag()->get('info'));
    }
}
