<?php

namespace OntoPress\Form\Resource;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * That is an example form
 */
class AddResourceDetailForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'label' => 'Titel',
                'required' => false,
                'attr' => array('class' => 'regular-text'),
            ))
            ->add('street', 'text', array(
                'label' => 'Straße',
                'required' => false,
                'attr' => array('class' => 'regular-text'),
            ))
            ->add('zip', 'text', array(
                'label' => 'Plz',
                'required' => false,
                'attr' => array('class' => 'regular-text'),
            ))
            ->add('submit', 'submit', array(
                'label' => 'Speichern',
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
