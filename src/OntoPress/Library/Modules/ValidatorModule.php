<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Validator\Validation;

class ValidatorModule extends AbstractModule
{
    public function process(ContainerBuilder $container)
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->setTranslator($container->get('symfony.translator'))
            ->setTranslationDomain('validators')
            ->getValidator();

        $container->set('symfony.validator', $validator);
    }
}
