<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Validator\Validation;

/**
 * Class ValidatorModule
 * Module loaded in AppKernel, used in various validations.
 */
class ValidatorModule extends AbstractModule
{
    /**
     * Adds ValidatorBuilder to given ContainerBuilder.
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
