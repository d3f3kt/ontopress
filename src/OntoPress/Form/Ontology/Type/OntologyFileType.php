<?php

namespace OntoPress\Form\Ontology\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Creates a form for uploading OntologyFiles.
 */
class OntologyFileType extends AbstractType
{
    /**
     * Lets the builder create a form to choose a file for the upload.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array(
                'label' => false,
            ));
    }

    /**
     * Sets the resolver back to default.
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OntoPress\Entity\OntologyFile',
            'label' => false,
        ));
    }

    /**
     * Returns class Prefix.
     *
     * @return string "ontologyFileType"
     */
    public function getName()
    {
        return 'ontologyFileType';
    }
}
