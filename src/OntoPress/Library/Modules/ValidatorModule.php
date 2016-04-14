<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Validator\Validation;

/**
 * Module which adds the Symfony Validator component to the dependency injection container.
 */
class ValidatorModule extends AbstractModule
{
    /**
     * Adds Validator to dependency injection container.
     *
     * @param ContainerBuilder $container
     */
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
