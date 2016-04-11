<?php

namespace OntoPress\Tests;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use OntoPress\Library\OntoPressTestCase;
use OntoPress\Service\DoctrineManager;

class DoctrineManagerTest extends OntoPressTestCase
{
    private $doctrine;

    public function setUp()
    {
        parent::setUp();

        $this->doctrine = static::getContainer()->get('ontopress.doctrine_manager');
    }

    public function tearDown()
    {
        unset($this->doctrine);
        parent::tearDown();
    }

    public function testGetter()
    {
        //$persistentObject = ;
        //$persistentManagerName = ;
        //$class = ;
        $this->doctrine->getDefaultManagerName();
        $this->doctrine->getManager();
        $this->doctrine->getManagers();
        //$this->getRepository($persistentObject, $persistentManagerName);
        //$this->getManagerForClass($class);
    }

    public function testDummyFunctions()
    {

        $this->doctrine->resetManager();
        $this->doctrine->getAliasNamespace(null);
        $this->doctrine->getManagerNames();
        $this->doctrine->getDefaultConnectionName();
        /*
        $this->doctrine->getConnection(null);
        $this->doctrine->getConnections();
        $this->doctrine->getConnectionName();
        */
    }
}
