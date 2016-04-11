<?php

namespace OntoPress\Tests;

use Brain\Monkey\Functions;
use Symfony\Component\HttpFoundation\Request;
use OntoPress\Entity\Ontology;
use OntoPress\Controller\OntologyController;
use OntoPress\Library\OntoPressWPTestCase;
use OntoPress\Tests\Entity\OntologyTest;
use OntoPress\Tests\TestHelper;

/**
 * Class OntologyControllerTest
 * Creates an OntologyController and tests it.
 */
class OntologyControllerTest extends OntoPressWPTestCase
{
    /**
     * @var OntologyController
     */
    private $ontologyController;

    public function setUp()
    {
        parent::setUp();
        $this->ontologyController = new OntologyController(static::getContainer());
    }

    public function tearDown()
    {
        unset($this->ontologyController);
        parent::tearDown();
    }

    public function testOntologyPages()
    {
        $manageOutput = $this->ontologyController->showManageAction();

        $this->assertContains('Ontologie Verwaltung', $manageOutput);
    }

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

    public function testDeleteAction()
    {
        // create test ontology
        $testOntology = new Ontology();
        $testOntology->setName('testOntology')
            ->setAuthor('testAuthor')
            ->setDate(new \DateTime());
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
        $this->assertContains('keine Ontologie', $withOutId);

        // test with correct id
        $withCorrectId = $this->ontologyController->showDeleteAction(
            new Request(array(
                'id' => $testOntology->getId(),
            ))
        );
        $this->assertContains('testOntology', $withCorrectId);

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
