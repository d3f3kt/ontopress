<?php

namespace OntoPress\Tests\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyFile;
use OntoPress\Library\OntoPressTestCase;

class OntologyTest extends OntoPressTestCase
{
    /**
     * Ontology Entity.
     *
     * @var Ontology
     */
    private $ontology;

    /**
     * Dummy Date.
     *
     * @var \DateTime
     */
    private $dummyDate;

    /**
     * Ontology File entity.
     *
     * @var OntologyFile
     */
    private $ontologyFile;

    public function setUp()
    {
        $this->dummyDate = new \DateTime();

        $this->ontology = new Ontology();
        $this->ontology->setName('TestOntology')
            ->setAuthor('TestAuthor')
            ->setDate($this->dummyDate);

        $this->ontologyFile = new OntologyFile();
    }

    public function tearDown()
    {
        unset($this->ontology);
        unset($this->ontologyFile);
    }

    /**
     * Test Basic setter and getter.
     */
    public function testOntologyBasic()
    {
        $this->assertEquals($this->ontology->getName(), 'TestOntology');
        $this->assertEquals($this->ontology->getAuthor(), 'TestAuthor');
        $this->assertEquals($this->ontology->getDate(), $this->dummyDate);
    }

    /**
     * Test the upload of an ontology file.
     */
    public function testOntologyFileUpload()
    {
        $testFile = self::createTmpFile('place-ontology.ttl');
        $this->ontologyFile->setFile($testFile);

        $this->ontology->addOntologyFile($this->ontologyFile);
        $this->assertEquals($this->ontology->getOntologyFiles()->count(), 1);
        $this->ontology->removeOntologyFile($this->ontologyFile);
        $this->assertEquals($this->ontology->getOntologyFiles()->count(), 0);
        $this->ontology->addOntologyFile($this->ontologyFile);

        $this->ontology->uploadFiles();
        $this->assertEquals($this->ontology->getId(), null);

        return $this->ontology;
    }

    /**
     * Test what happens if the upload method is called with not file set before.
     */
    public function testOntologyFile()
    {
        $this->ontologyFile->upload();
        $this->assertEquals($this->ontologyFile->getPath(), null);
    }

    /**
     * @depends testOntologyFileUpload
     */
    public function testOntologyFileCombined(Ontology $ontology)
    {
        $ontologyFile = $ontology->getOntologyFiles()->first();

        $this->assertContains('place', $ontologyFile->getPath());
        $this->assertEquals($ontology, $ontologyFile->getOntology());
        $this->assertEquals($ontologyFile->getId(), null);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\File\UploadedFile', $ontologyFile->getFile());
        $this->assertFileExists($ontologyFile->getAbsolutePath());
    }

    /**
     * Create temporary ttl file for ontology upload.
     */
    static public function createTmpFile($fileName)
    {
        $rootDir = static::getContainer()->getParameter('ontopress.root_dir');
        $tmpFileName = tempnam(sys_get_temp_dir(), 'OntoPress_');
        copy($rootDir.'/Tests/TestFiles/'.$fileName, $tmpFileName);

        $uploadFile = new UploadedFile(
            $tmpFileName,
            $fileName,
            'text/turtle',
            null,
            null,
            true
        );

        return $uploadFile;
    }
}
