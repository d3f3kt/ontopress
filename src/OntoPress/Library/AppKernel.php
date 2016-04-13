<?php

namespace OntoPress\Library;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Composer\Autoload\ClassLoader;

/**
 * Class AppKernel
 * Loads various modules/extensions and binds them into the project.
 */
class AppKernel
{
    /**
     * ContainerBuilder Entity. Compiles after modules are loaded.
     * @var ContainerBuilder
     */
    private $containerBuilder;

    /**
     * Regulates working environment.
     * @var string $environment
     */
    private $environment;

    /**
     * ClassLoader used create new service definition in the ContainerBuilder.
     * @var ClassLoader $loader
     */
    private $loader;

    /**
     * AppKernel constructor.
     * @param string $environment
     * @param ClassLoader $loader
     */
    public function __construct($environment, ClassLoader $loader)
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->environment = $environment;
        $this->loader = $loader;

        $this->setPathes();
        $this->loadServiceConfig();

        $this->registerModules(array(
            'DoctrineModule',
            'TranslatorModule',
            'ValidatorModule',
            'TwigModule',
            'FormModule',
            'SessionModule',
            'TwigExtensionModule',
        ));

        $this->containerBuilder->compile();

        if ($this->environment == 'test') {
            $this->getContainer()->get('ontopress.doctrine_schema_tool')->updateSchema();
        }
    }

    /**
     * Getter for containerBuilder.
     * @return ContainerBuilder $containerBuilder
     */
    public function getContainer()
    {
        return $this->containerBuilder;
    }

    /**
     * Sets resource parameters in the ContainerBuilder.
     */
    private function setPathes()
    {
        $this->containerBuilder->setParameter('ontopress.root_dir', __DIR__.'/../');
        $this->containerBuilder->setParameter('ontopress.plugin_dir', __DIR__.'/../../../');
        $this->containerBuilder->setParameter('ontopress.entity_dir', __DIR__.'/../Entity');
        $this->containerBuilder->setParameter('ontopress.config_dir', __DIR__.'/../Resources/config');
        $this->containerBuilder->setParameter('ontopress.views_dir', __DIR__.'/../Resources/views');
    }

    /**
     * Loads service parameters from the ContainerBuilder.
     */
    private function loadServiceConfig()
    {
        $loader = new YamlFileLoader(
            $this->containerBuilder,
            new FileLocator($this->containerBuilder->getParameter('ontopress.config_dir'))
        );
        $loader->load('services.yml');
    }

    /**
     * Registers every module at the ContainerBuilder, using the ClassLoader.
     * @param array $moduleArray
     */
    private function registerModules($moduleArray)
    {
        foreach ($moduleArray as $module) {
            $moduleClass = 'OntoPress\Library\Modules\\'.$module;
            $this->containerBuilder->addCompilerPass(
                new $moduleClass($this->environment, $this->loader)
            );
        }
    }
}
