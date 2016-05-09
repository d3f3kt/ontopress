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
     * Creates a form by adding a required number of OntologyFiles.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
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
                'label' => 'Ontologie Dateien:',
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
     *
     * @param OptionsResolverInterface $resolver
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
     *
     * @return string "ontologyAddType"
     */
    public function getName()
    {
        return 'ontologyAddType';
    }
}
