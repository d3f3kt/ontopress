<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Form\Forms;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Translation\Translator;

/**
 * Class FormModule
 * Form module, loaded in the AppKernel to use various FormFactory Extensions.
 */
class FormModule extends AbstractModule
{
    /**
     * Loads the module into a ContainerBuilder.
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $formEngine = new TwigRendererEngine(array('form/base.html.twig'));
        $formEngine->setEnvironment($container->get('symfony.twig'));
        $formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new ValidatorExtension($container->get('symfony.validator')))
            ->addExtension(new HttpFoundationExtension())
            ->getFormFactory();

        $container->set('symfony.form_engine', $formEngine);
        $container->set('symfony.form', $formFactory);
    }
}
