<?php

namespace OntoPress\Form\Ontology\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OntoPress\Form\Base\SubmitCancelType;

/**
 * That is an example form.
 */
class DeleteOntologyType extends AbstractType
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired('cancel_link');
        $resolver->setDefaults(array(
            'data_class' => 'OntoPress\Entity\Ontology',
            'attr' => array('class' => 'form-table'),
            'cancel_label' => 'Abbrechen',
            'cascade_validation' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ontologyAddType';
    }
}
