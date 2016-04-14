<?php

namespace OntoPress\Form\Base;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Creates a submit button and a cancel button within the buildView.
 */
class SubmitCancelType extends SubmitType
{
    /**
     * Changes OptionResolver to fit the buttons.
     * @param OptionsResolver $resolver
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'cancel_label' => false,
            'cancel_link' => false,
        ));
    }

    /**
     * Creates the view, giving it to the superclass.
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['cancel_label'] = $options['cancel_label'];
        $view->vars['cancel_link'] = $options['cancel_link'];
        parent::buildView($view, $form, $options);
    }

    /**
     * Returns the submitCancel Prefix of the class.
     * @return string "submitCancel"
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'submitCancel';
    }
}
