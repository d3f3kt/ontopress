<?php

namespace OntoPress\Form\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * That is an example form.
 */
class EditFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Name',
                'required' => false,
                'attr' => array('class' => 'regular-text'),
            ))
            ->add('twig', 'textarea', array(
                'label' => 'Twig Code',
                'attr' => array(
                    'cols' => 45,
                    'rows' => 15,
                ),
            ))
            ->add('twigTheme', 'file')
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
