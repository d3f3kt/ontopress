<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\DumpExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Component\VarDumper\Cloner\VarCloner;

/**
 * Module which adds the FormExtension, TranslationExtension and DumpExtension to Twig environment.
 * Furthermore all services which are tagged to used as a twig extension gets added too.
 */
class TwigExtensionModule extends AbstractModule
{
    /**
     * Adds hard coded twig extensions and tagged services to twig environment.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $container->get('symfony.twig')->addExtension(new FormExtension(new TwigRenderer($container->get('symfony.form_engine'))));
        $container->get('symfony.twig')->addExtension(new TranslationExtension($container->get('symfony.translator')));
        $container->get('symfony.twig')->addExtension(new \Twig_Extension_StringLoader());

        if (!$this->isProdEnv()) {
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
