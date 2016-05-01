<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Validator\Validation;
use OntoPress\Library\ConstraintValidatorFactory;

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
        $validators = array();
        foreach ($container->findTaggedServiceIds('validator.constraint_validator') as $id => $tag) {
            $validators[$id] = $id;
        }

        $validatorConstraintFactory = new ConstraintValidatorFactory(
            $container,
            $validators
        );

        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->setTranslator($container->get('symfony.translator'))
            ->setTranslationDomain('validators')
            ->setConstraintValidatorFactory($validatorConstraintFactory)
            ->getValidator();

        $container->set('symfony.validator', $validator);
    }
}
