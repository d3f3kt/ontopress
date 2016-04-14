<?php

namespace OntoPress\Form\Ontology\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OntoPress\Form\Base\SubmitCancelType;

/**
 * Creates a form for adding one or more Ontologies to the database.
 */
class AddOntologyType extends AbstractType
{
    /**
     * Lets the builder create a form for adding OntologyFiles together, from one to a wished number.
     * The number of OntologyFiles is regulated over an addButton, which the uploader can use.
     * @param FormBuilderInterface $builder
     * @param array $options
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Ontologiename: ',
                'attr' => array('class' => 'regular-text'),
            ))
            ->add('ontologyFiles', 'collection', array(
                'type' => new OntologyFileType(),
                'allow_add' => true,
                'by_reference' => false,
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
            'data_class' => 'OntoPress\Entity\Ontology',
            'attr' => array('class' => 'form-table'),
            'cancel_label' => 'Abbrechen',
            'cascade_validation' => true,
        ));
    }

    /**
     * Returns the class Prefix ontologyAddType.
     * @return string "ontologyAddType"
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ontologyAddType';
    }
}
