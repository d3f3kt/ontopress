<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Composer\Autoload\ClassLoader;

/**
 * Abstract module structure to add a service to the dependency injection container.
 */
abstract class AbstractModule implements CompilerPassInterface
{
    /**
     * Environment String.
     *
     * @var string
     */
    protected $environment;

    /**
     * Compoer Class Loader.
     *
     * @var ClassLoader
     */
    protected $classLoader;

    /**
     * Initialize OntoPress Module.
     *
     * @param string      $environment environment string (prod|dev|test)
     * @param ClassLoader $classLoader Composer Class Loader
     */
    public function __construct($environment, ClassLoader $classLoader)
    {
        $this->environment = $environment;
        $this->classLoader = $classLoader;
    }

    /**
     * Process Container Pass.
     *
     * @param ContainerBuilder $container Container Builder
     */
    abstract public function process(ContainerBuilder $container);
}
