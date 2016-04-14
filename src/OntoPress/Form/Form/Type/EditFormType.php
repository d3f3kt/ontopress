<?php

namespace OntoPress\Form\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OntoPress\Form\Base\SubmitCancelType;

/**
 * That is an example form.
 */
class EditFormType extends AbstractType
{
    /**
     * Lets the builder create an example form, with a name label and cancel/submit button.
     * @param FormBuilderInterface $builder
     * @param array $options
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
            ->add('twigCode', 'textarea', array(
                'label' => 'Twig Code',
                'attr' => array(
                    'style' => 'width: 90%',
                    'rows' => 15,
                ),
            ))
            ->add('submit', new SubmitCancelType(), array(
                'label' => 'Speichern',
                'attr' => array('class' => 'button button-primary'),
                'cancel_link' => $options['cancel_link'],
                'cancel_label' => $options['cancel_label'],
            ));
    }

    /**
     * Sets the resolver back to default.
     * @param OptionsResolverInterface $resolver
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired('cancel_link');
        $resolver->setDefaults(array(
            'attr' => array('class' => 'form-table'),
            'cancel_label' => 'Abbrechen',
        ));
    }

    /**
     * Returns class Prefix formEditType.
     * @return string "formEditType"
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'formEditType';
    }
}
