<?php

namespace OntoPress\Tests;

use OntoPress\Library\OntoPressTestCase;
use OntoPress\Service\DoctrineManager;

class DoctrineManagerTest extends OntoPressTestCase
{
    private $doctrineManager;
    private $doctrine;

    public function setUp()
    {
        parent::setUp();

        $this->doctrine = static::getContainer()->get('doctrine');
        $this->doctrineManager = static::getContainer()->get('ontopress.doctrine_manager');
    }

    public function tearDown()
    {
        unset($this->doctrine);
        parent::tearDown();
    }

    public function testDoctrineManager()
    {
        $this->assertEquals($doctrine, $doctrineManager->getDefaultManagerName());
        $this->assertEquals($doctrine, $doctrineManager->getManager());
        $this->assertEquals(array($doctrine), $doctrineManger->getManagers());
        $this->assertEquals(null, $doctrineManager->resetManager());
        $this->assertEquals(null, $doctrineManage->getAliasNamespace());
        $this->asserrtEquals(null, $doctrineManger->getManagerNames());
        $this->assertEquals(
            $this->doctrine->getRepository('OntoPress\Entity\Ontology'),
            $this->doctrineManager->getRepository('OntoPress\Entity\Ontology')
        );
        $this->assertEquals(null, $this->doctrineManager->getManagerForClass('Ontology'));
        $this->assertEquals(null, $this->getDefaultConnectionName());
        $this->assertEquals(null, $this->getConnection());
        $this->assertEquals(null, $this->getConnections());
        $this->assertEquals(null, $this->getConnectionsNames());
    }
}
