<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Loader\XliffFileLoader;

/**
 * Class TranslatorModule
 * Module loaded in AppKernel, to use XliffFileLoader.
 */
class TranslatorModule extends AbstractModule
{
    /**
     * Adds translator with XliffFileLoader to given ContainerBuilder.
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $translator = new Translator('de', new MessageSelector());
        $translator->addLoader('xlf', new XliffFileLoader());

        $container->set('symfony.translator', $translator);
    }
}
