<?php

namespace OntoPress\Form\Resource;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OntoPress\Form\Base\SubmitCancelType;

/**
 * That is an example form.
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
            ->add('submit', new SubmitCancelType(), array(
                'label' => 'Speichern',
                'attr' => array('class' => 'button button-primary'),
                'cancel_link' => $options['cancel_link'],
                'cancel_label' => $options['cancel_label'],
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        //$resolver->setRequired('cancel_link');
        $resolver->setDefaults(array(
            'attr' => array('class' => 'form-table'),
            'cancel_label' => 'Abbrechen',
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
