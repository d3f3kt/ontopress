<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\DataOntology;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyFile;
use OntoPress\Entity\Form;
use OntoPress\Library\OntoPressTestCase;

class OntologyTest extends OntoPressTestCase
{
    /**
     * Ontology entity.
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

    /**
     * DataOntology entity.
     *
     * @var DataOntology
     */
    private $dataOntology;

    /**
     * OntologyForm entity.
     *
     * @var OntologyForm
     */
    private $ontologyForm;

    /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        $this->dummyDate = new \DateTime();

        $this->ontology = new Ontology();
        $this->ontology->setName('TestOntology')
            ->setAuthor('TestAuthor')
            ->setDate($this->dummyDate);

        $this->ontologyFile = new OntologyFile();
        $this->dataOntology = new DataOntology();
        $this->ontologyForm = new Form();
    }

    /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
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
     * Test what happens if the upload method is called with no file set before.
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
     * Test add/remove of DataOntologies and DataOntologies getter
     */
    public function testAddRemoveDataOntologies()
    {
        $this->ontology->addDataOntology($this->dataOntology);
        $this->assertEquals($this->ontology->getDataOntologies()[0], $this->dataOntology);
        $this->ontology->removeDataOntology($this->dataOntology);
        $this->assertEmpty($this->ontology->getDataOntologies());
    }

    public function testAddRemoveOntologyForms()
    {
        $this->ontology->addOntologyForm($this->ontologyForm);
        $this->assertEquals($this->ontology->getOntologyForms()[0], $this->ontologyForm);
        $this->ontology->removeOntologyForm($this->ontologyForm);
        $this->assertEmpty($this->ontology->getOntologyForms());
    }

    /**
     * Create temporary ttl file for ontology upload.
     */
    public static function createTmpFile($fileName)
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
