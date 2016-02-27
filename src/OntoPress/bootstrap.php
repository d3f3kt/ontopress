<?php

$vendorDir = __DIR__.'/../../vendor';
$viewsDir = __DIR__.'/Resources/views';
$loader = require $vendorDir.'/autoload.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Form\Forms;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Loader\XliffFileLoader;

$container = new ContainerBuilder();

// twig setup
$appVariableReflection = new \ReflectionClass('\Symfony\Bridge\Twig\AppVariable');
$vendorTwigBridgeDir = dirname($appVariableReflection->getFileName());
$twig = new Twig_Environment(
    new Twig_Loader_Filesystem(
        array(
            $viewsDir,
            $vendorTwigBridgeDir.'/Resources/views/Form',
        )
    )
);

// form setup
$formEngine = new TwigRendererEngine(array('form/base.html.twig'));
$formEngine->setEnvironment($twig);
$formFactory = Forms::createFormFactoryBuilder()->getFormFactory();

// translator setup
$translator = new Translator('de', new MessageSelector());
$translator->addLoader('xlf', new XliffFileLoader());

// add all twig extensions
$twig->addExtension(new FormExtension(new TwigRenderer($formEngine)));
$twig->addExtension(new TranslationExtension($translator));

$container->set('translator', $translator);
$container->set('form', $formFactory);
$container->set('twig', $twig);

return $container;
