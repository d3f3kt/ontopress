<?php

namespace OntoPress\Form\Ontology\Type;

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
            ->add('name', 'text', array(
                'label' => 'Ontologiename: ',
                'attr' => array('class' => 'regular-text'),
            ))
            ->add('ontologyFile', 'file', array(
                'label' => 'Ontologie',
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
            'data_class' => 'OntoPress\Form\Ontology\Model\AddOntology',
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
