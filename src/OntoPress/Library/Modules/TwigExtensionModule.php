<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\DumpExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Component\VarDumper\Cloner\VarCloner;

/**
 * Class TwigExtensionModule
 * Module loaded in AppKernel, to use multiple Twig Extension.
 * Namely FormExtension, TranslationExtension and if not in development mode, DumpExtension.
 */
class TwigExtensionModule extends AbstractModule
{
    /**
     * Adds extension services to all twig templates of given ContainerBuilder.
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $container->get('symfony.twig')->addExtension(new FormExtension(new TwigRenderer($container->get('symfony.form_engine'))));
        $container->get('symfony.twig')->addExtension(new TranslationExtension($container->get('symfony.translator')));

        if ($this->environment != 'prod') {
            $container->get('symfony.twig')->addExtension(new DumpExtension(new VarCloner()));
        }

        $taggedServices = $container->findTaggedServiceIds(
            'twig.extension'
        );

        foreach ($taggedServices as $id => $tags) {
            $container->get('symfony.twig')->addExtension($container->get($id));
        }
    }
}
