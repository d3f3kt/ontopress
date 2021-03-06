<?php

namespace OntoPress\Tests;

use Brain\Monkey\Functions;
use Symfony\Component\HttpFoundation\Request;
use OntoPress\Controller\OntologyController;
use OntoPress\Library\OntoPressWPTestCase;
use OntoPress\Tests\Entity\OntologyTest;

/**
 * Class OntologyControllerTest
 * Creates an OntologyController and tests it.
 */
class OntologyControllerTest extends OntoPressWPTestCase
{
    /**
     * OntologyController Entity.
     * @var OntologyController
     */
    private $ontologyController;

    /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        parent::setUp();
        $this->ontologyController = new OntologyController(static::getContainer());
    }

    /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
    public function tearDown()
    {
        unset($this->ontologyController);
        parent::tearDown();
    }

    /**
     * Tests showManageAction method, should return a rendered twig template.
     */
    public function testOntologyPages()
    {
        $manageOutput = $this->ontologyController->showManageAction(new Request());

        $this->assertContains('Ontologie Verwaltung', $manageOutput);
    }

    /**
     * Tests showAddAction method, should add an ontology and return a rendered twig template.
     */
    public function testAddAction()
    {
        Functions::when('wp_get_current_user')->alias(array('OntoPress\Tests\TestHelper', 'emulateWPUser'));

        $addRawOutput = $this->ontologyController->showAddAction(new Request());
        $this->assertContains('Ontologie hochladen', $addRawOutput);

        $uploadOutput = $this->ontologyController->showAddAction(
            new Request(
                array(),
                array('ontologyAddType' => array(
                    'name' => 'testAddOntology',
                    'submit' => '',
                )),
                array(),
                array(),
                array('ontologyAddType' => array(
                    'ontologyFiles' => array(
                        array('file' => OntologyTest::createTmpFile('place-ontology.ttl')),
                        array('file' => OntologyTest::createTmpFile('knorke.ttl')),
                    ),
                )),
                array('REQUEST_METHOD' => 'POST')
            )
        );
        $uploadedOntology = static::getContainer()->get('doctrine')
            ->getRepository('OntoPress\Entity\Ontology')
            ->findOneByName('testAddOntology');

        $this->assertContains('window.location', $uploadOutput);
        $this->assertEquals($uploadedOntology->getOntologyFiles()->count(), 2);
    }

    /**
     * Tests showDeleteAction method, should remove a selected ontology and return a rendered twig template.
     */
    public function testDeleteAction()
    {
        // create test ontology
        $testOntology = TestHelper::createTestOntology();

        static::getContainer()->get('doctrine')->persist($testOntology);
        static::getContainer()->get('doctrine')->flush();

        // test wihtout any id
        $withOutId = $this->ontologyController->showDeleteAction(new Request());
        $this->assertContains('keine Ontologie', $withOutId);

        // test with wrong id
        $withWrongId = $this->ontologyController->showDeleteAction(
            new Request(array(
                'id' => 1337,
            ))
        );
        $this->assertContains('keine Ontologie', $withWrongId);

        // test with correct id
        $withCorrectId = $this->ontologyController->showDeleteAction(
            new Request(array(
                'id' => $testOntology->getId(),
            ))
        );
        $this->assertContains('Test Ontology', $withCorrectId);

        // test whole delete process
        $deleted = $this->ontologyController->showDeleteAction(
            new Request(
                array('id' => $testOntology->getId()),
                array('ontologyDeleteType' => array(
                    'submit' => '',
                )),
                array(),
                array(),
                array(),
                array('REQUEST_METHOD' => 'POST')
            )
        );
        $this->assertContains('window.location', $deleted);
        $this->assertEquals($testOntology->getId(), null);
    }
}
