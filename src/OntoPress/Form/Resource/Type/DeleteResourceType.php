<?php

namespace OntoPress\Form\Resource\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OntoPress\Form\Base\SubmitCancelType;

/**
 * Creates a form to check if deletion of a resource is wanted.
 */
class DeleteResourceType extends AbstractType
{
    /**
     * Lets the FormBuilderInterface create a form with a cancel and submit button, to check if the deletion is wanted.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('submit', new SubmitCancelType(), array(
                'label' => 'Löschen',
                'attr' => array('class' => 'button button-primary'),
                'cancel_link' => $options['cancel_link'],
                'cancel_label' => $options['cancel_label'],
            ));
    }

    /**
     * Sets the resolver back to default.
     *
     * @param OptionsResolverInterface $resolver
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
     * Returns class Prefix resourceDeleteType.
     *
     * @return string "resourceDeleteType"
     */
    public function getName()
    {
        return 'resourceDeleteType';
    }
}
