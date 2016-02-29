<?php

namespace OntoPress\Form\Ontology;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * That is an example form
 */
class AddOntologyForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ontologyName', 'text', array(
                'label' => 'Ontologiename: ',
                'required' => false,
                'attr' => array('class' => 'regular-text'),
            ))
            ->add('ontology', 'text', array(
                'label' => 'Ontologie',
                'required' => false,
                'attr' => array('class' => 'regular-text'),
            ))
            ->add('submit', 'submit', array(
                'label' => 'Upload',
                'attr' => array('class' => 'button button-primary'),
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array('class' => 'form-table'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'networkMessage';
    }
}
