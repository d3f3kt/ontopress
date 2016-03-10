<?php

$vendorDir = __DIR__.'/../../vendor';
$viewsDir = __DIR__.'/Resources/views';
$entitiesDir = array(__DIR__.'/Entity');
$loader = require $vendorDir.'/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Form\Forms;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Loader\XliffFileLoader;

$container = new ContainerBuilder();

// doctrine setup
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => DB_USER,
    'password' => DB_PASSWORD,
    'dbname'   => DB_NAME,
    'host'     => DB_HOST
);

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
$doctrineConfig = Setup::createAnnotationMetadataConfiguration($entitiesDir, false, null, null, false);
$entityManager = EntityManager::create($dbParams, $doctrineConfig);

// translator setup
$translator = new Translator('de', new MessageSelector());
$translator->addLoader('xlf', new XliffFileLoader());

// validator
$validator = Validation::createValidatorBuilder()
    ->enableAnnotationMapping()
    ->setTranslator($translator)
    ->setTranslationDomain('validators')
    ->getValidator();

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
$formFactory = Forms::createFormFactoryBuilder()
    ->addExtension(new ValidatorExtension($validator))
    ->getFormFactory();

// add all twig extensions
$twig->addExtension(new FormExtension(new TwigRenderer($formEngine)));
$twig->addExtension(new TranslationExtension($translator));

$container->set('translator', $translator);
$container->set('form', $formFactory);
$container->set('twig', $twig);
$container->set('doctrine', $entityManager);
$container->set('validator', $validator);

return $container;
