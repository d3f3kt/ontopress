<?php

namespace OntoPress\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * Class DoctrineSchemaTool
 *
 * The SchemaTool is a tool to create/drop/update database schemas based on Metadata.
 */
class DoctrineSchemaTool
{
    /**
     * Doctrine SchemaTool.
     *
     * @var schemaTool
     */
    private $schemaTool;

    /**
     * Doctrine Meta Data.
     *
     * @var metaDatas
     */
    private $metaDatas;

    /**
     * The Constructor is automatically called by creating a new DoctrineSchemaTool.
     * It initializes a new SchemaTool instance that uses the connection of the provided EntityManager.
     *
     * @param EntityManager $doctrine Doctrine EntityManager
     */
    public function __construct(EntityManager $doctrine)
    {
        $this->metaDatas = $doctrine->getMetadataFactory()->getAllMetadata();
        $this->schemaTool = new SchemaTool($doctrine);
    }

    /**
     * Updates the database schema.
     */
    public function updateSchema()
    {
        $this->schemaTool->updateSchema($this->metaDatas, true);
    }

    /**
     * Drops all elements in the database of the current connection.
     */
    public function dropSchema()
    {
        $this->schemaTool->dropSchema($this->metaDatas);
    }
}
