<?php

namespace OntoPress\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

class DoctrineSchemaTool
{
    private $schemaTool;
    private $metaDatas;

    public function __construct(EntityManager $doctrine)
    {
        $this->metaDatas = $doctrine->getMetadataFactory()->getAllMetadata();
        $this->schemaTool = new SchemaTool($doctrine);
    }

    public function updateSchema()
    {
        $this->schemaTool->updateSchema($this->metaDatas);
    }

    public function dropSchema()
    {
        $this->schemaTool->dropSchema($this->metaDatas);
    }
}
