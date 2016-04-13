<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * Class DoctrineModule
 * Doctrine module, loaded in the AppKernel to access environment database.
 */
class DoctrineModule extends AbstractModule
{
    /**
     * Loads the module into a ContainerBuilder.
     * @param ContainerBuilder $container
     * @throws \Doctrine\ORM\ORMException
     */
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

    /**
     * Method to access database parameters of the environment.
     * @return array
     */
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
