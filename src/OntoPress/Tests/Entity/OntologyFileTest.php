<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\Ontology;
use OntoPress\Library\OntoPressTestCase;
use OntoPress\Entity\OntologyFile;
use OntoPress\Tests\TestHelper;

/**
 * Class OntologyFileTest
 * Creates an OntologyFile and tests it.
 */
class OntologyFileTest extends OntoPressTestCase
{
    /**
     * OntologyFile Entity.
     * @var OntologyFile
     */
    private $ontologyFile;

    /**
     * Ontology Entity.
     * @var Ontology
     */
    private $ontology;

    /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        parent::setUp();
        $this->ontology = TestHelper::createTestOntology();
        $this->ontologyFile = new OntologyFile();
        $this->ontologyFile->setOntology($this->ontology)
                            ->setPath("test/testpath")
                            ->setFile();
    }

    /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
    public function tearDown()
    {
        unset($this->ontology);
        unset($this->ontologyFile);
        parent::tearDown();
    }

    /**
     * Tests OntologyFile basic set/get-methods, which should return the new or changed attributes.
     */
    public function testOntologyFileBasic()
    {
        $this->assertEquals($this->ontologyFile->getPath(), "test/testpath");
        $this->assertEquals($this->ontologyFile->getOntology(), $this->ontology);
        $this->assertNull($this->ontologyFile->getId());
        $this->assertNull($this->ontologyFile->getFile());
    }

    /**
     * Tests upload method, should return null, when nothing is uploaded.
     */
    public function testUpload()
    {
        $this->assertNull($this->ontologyFile->upload());
    }
}
