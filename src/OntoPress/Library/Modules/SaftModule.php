<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Saft\Addition\EasyRdf\Data\ParserEasyRdf;
use Saft\Addition\ARC2\Store\ARC2;
use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\StatementFactoryImpl;
use Saft\Rdf\StatementIteratorFactoryImpl;
use Saft\Sparql\Query\QueryFactoryImpl;
use Saft\Sparql\Result\ResultFactoryImpl;
use Saft\Store\BasicTriplePatternStore;
use Saft\Store\Store as StoreInterface;

/**
 * Module which adds Saft components to dependency injection container.
 */
class SaftModule extends AbstractModule
{
    /**
     * Loads the module into a ContainerBuilder.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $parser = new ParserEasyRdf(
            new NodeFactoryImpl(),
            new StatementFactoryImpl(),
            'turtle'
        );

        $container->set('saft.easyrdf.parser', $parser);
        $container->set('saft.store', $this->getStore());
    }

    /**
     * Get Saft store according to the environment.
     *
     * @return StoreInterface Saft Store interface
     */
    private function getStore()
    {
        if ($this->isTestEnv()) {
            return new BasicTriplePatternStore(
                new NodeFactoryImpl(),
                new StatementFactoryImpl(),
                new QueryFactoryImpl(),
                new StatementIteratorFactoryImpl()
            );
        } else {
            return new ARC2(
                new NodeFactoryImpl(),
                new StatementFactoryImpl(),
                new QueryFactoryImpl(),
                new ResultFactoryImpl(),
                new StatementIteratorFactoryImpl(),
                array(
                    'username' => DB_USER,
                    'password' => DB_PASSWORD,
                    'host' => DB_HOST,
                    'database' => DB_NAME,
                    'table-prefix' => 'ontopress_arc2_',
                )
            );
        }
    }
}
