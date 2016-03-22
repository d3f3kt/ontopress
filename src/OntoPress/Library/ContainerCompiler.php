<?php

namespace OntoPress\Library;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ContainerCompiler implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds(
            'twig.extension'
        );

        foreach ($taggedServices as $id => $tags) {
            $container->get('twig')->addExtension($container->get($id));
        }
    }
}
