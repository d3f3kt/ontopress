<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Annotations\AnnotationRegistry;

class DoctrineModule extends AbstractModule
{
    public function process(ContainerBuilder $container)
    {
        AnnotationRegistry::registerLoader(array($this->classLoader, 'loadClass'));
        $doctrineConfig = Setup::createAnnotationMetadataConfiguration(
            array(
                $container->getParameter('ontopress.entity_dir'),
            ),
            in_array($this->environment, array('dev', 'test')),
            null,
            null,
            false
        );
        $entityManager = EntityManager::create($this->getDatabaseParameters(), $doctrineConfig);

        $container->set('doctrine', $entityManager);
    }

    private function getDatabaseParameters()
    {
        if ($this->environment == 'test') {
            $dbParams = array(
                'driver' => 'pdo_sqlite',
                'memory' => true,
            );
        } else {
            $dbParams = array(
                'driver' => 'pdo_mysql',
                'user' => DB_USER,
                'password' => DB_PASSWORD,
                'dbname' => DB_NAME,
                'host' => DB_HOST,
            );
        }

        return $dbParams;
    }
}
