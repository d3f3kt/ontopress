<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\DumpExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Component\VarDumper\Cloner\VarCloner;

class TwigModule extends AbstractModule
{
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
