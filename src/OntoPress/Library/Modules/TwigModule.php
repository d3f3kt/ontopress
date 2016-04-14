<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Module which adds the twig environment to the dependency injection container.
 */
class TwigModule extends AbstractModule
{
    /**
     * Adds twig environment to dependency injection container.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $appVariableReflection = new \ReflectionClass('\Symfony\Bridge\Twig\AppVariable');
        $vendorTwigBridgeDir = dirname($appVariableReflection->getFileName());

        $twig = new \Twig_Environment(
            new \Twig_Loader_Filesystem(
                array(
                    $container->getParameter('ontopress.views_dir'),
                    $vendorTwigBridgeDir.'/Resources/views/Form',
                )
            )
        );

        if ($this->environment != 'prod') {
            $twig->enableDebug();
        }

        $container->set('symfony.twig', $twig);
    }
}
