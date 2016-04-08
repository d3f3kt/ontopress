<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\Ontology;
use OntoPress\Library\OntoPressTestCase;
use OntoPress\Entity\OntologyFile;

class OntologyFileTest extends OntoPressTestCase
{
    private $ontologyFile;
    private $ontology;

    public function setUp()
    {
        $this->ontologyFile = new OntologyFile();
        $this->ontology = new Ontology();

        $this->ontologyFile->setOntology($this->ontology)
                            ->setPath("test/testpath")
                            ->setFile();
    }

    public function tearDown()
    {
        unset($this->ontology);
        unset($this->ontologyFile);
    }

    public function testOntologyFileBasic()
    {
        $this->assertEquals($this->ontologyFile->getPath(), "test/testpath");
        $this->assertEquals($this->ontologyFile->getOntology(), $this->ontology);
        $this->assertNull($this->ontologyFile->getId());
        $this->assertNull($this->ontologyFile->getFile());
    }

    public function testGetDir()
    {
        /*
        // is different on testServer and main Server
        // /var/www/wp-content/plugins/ontologie/src/OntoPress/Entity/../Resources/ontology/upload/test/testpath
        $this->assertEquals($this->ontologyFile->getAbsolutePath(), "/builds/SWT-WordPress/plugins/src/OntoPress/Entity/../Resources/ontology/upload/test/testpath");

        // protected Access -> need Stub
        $this->assertEquals($this->ontologyFile->getUploadDir(), "/var/www/wp-content/plugins/ontologie/src/OntoPress/Entity/../Resources/ontology/upload");
        $this->assertEquals($this->ontologyFile->getUploadRootDir(), "/var/www/wp-content/plugins/ontologie/src/OntoPress/Entity/../Resources/");
        */
    }

    public function testUpload()
    {
        $this->assertNull($this->ontologyFile->upload());
    }
}
