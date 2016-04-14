<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Loader\XliffFileLoader;

/**
 * Module which adds the Symfony Translation component to the dependency injection container.
 */
class TranslatorModule extends AbstractModule
{
    /**
     * Adds translator with XliffFileLoader to dependency injection container.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $translator = new Translator('de', new MessageSelector());
        $translator->addLoader('xlf', new XliffFileLoader());

        $container->set('symfony.translator', $translator);
    }
}
