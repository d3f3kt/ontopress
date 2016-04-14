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

    /**
     * Check if we are in development environment.
     *
     * @return bool TRUE if dev environment FALSE otherwise
     */
    protected function isDevEnv()
    {
        return $this->environment == 'dev';
    }

    /**
     * Check if we are in test environment.
     *
     * @return bool TRUE if test environment FALSE otherwise
     */
    protected function isTestEnv()
    {
        return $this->environment == 'test';
    }

    /**
     * Check if we are in prod environment.
     *
     * @return bool TRUE if prod environment FALSE otherwise
     */
    protected function isProdEnv()
    {
        return $this->environment == 'prod';
    }
}
