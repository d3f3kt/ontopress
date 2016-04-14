<?php

namespace OntoPress\Tests;

use OntoPress\Library\OntoPressTestCase;
use OntoPress\Service\DoctrineManager;

/**
 * Class DoctrineManagerTest
 * Creates a DoctrineManager and test it.
 */
class DoctrineManagerTest extends OntoPressTestCase
{
    /**
     * DoctrineManager Entity.
     * @var DoctrineManager
     */
    private $doctrineManager;

    /**
     * Doctrine Entity.
     * @var
     */
    private $doctrine;

    /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        parent::setUp();

        $this->doctrine = static::getContainer()->get('doctrine');
        $this->doctrineManager = static::getContainer()->get('ontopress.doctrine_manager');
    }

    /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
    public function tearDown()
    {
        unset($this->doctrine);
        unset($this->doctrineManager);
        parent::tearDown();
    }

    /**
     * Tests all DoctrineManager methods.
     */
    public function testDoctrineManager()
    {
        $this->assertEquals($this->doctrine, $this->doctrineManager->getDefaultManagerName());
        $this->assertEquals($this->doctrine, $this->doctrineManager->getManager());
        $this->assertEquals(array($this->doctrine), $this->doctrineManager->getManagers());
        $this->assertEquals(null, $this->doctrineManager->resetManager());
        $this->assertEquals(null, $this->doctrineManager->getAliasNamespace('Ontology'));
        $this->assertEquals(null, $this->doctrineManager->getManagerNames());
        $this->assertEquals(
            $this->doctrine->getRepository('OntoPress\Entity\Ontology'),
            $this->doctrineManager->getRepository('OntoPress\Entity\Ontology')
        );
        $this->assertEquals($this->doctrine, $this->doctrineManager->getManagerForClass('Ontology'));
        $this->assertEquals(null, $this->doctrineManager->getDefaultConnectionName());
        $this->assertEquals(null, $this->doctrineManager->getConnection());
        $this->assertEquals(null, $this->doctrineManager->getConnections());
        $this->assertEquals(null, $this->doctrineManager->getConnectionNames());
    }
}
