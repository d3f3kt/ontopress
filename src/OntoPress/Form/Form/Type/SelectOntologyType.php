<?php

namespace OntoPress\Form\Form\Type;

use OntoPress\Service\DoctrineManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OntoPress\Form\Base\SubmitCancelType;
use Doctrine\ORM\EntityRepository;

/**
 * Show all Ontologies in select choice type.
 */
class SelectOntologyType extends AbstractType
{
    /**
     * Lets builder list all Ontologies in a choice type field, together with a cancel/submit button.
     * @param FormBuilderInterface $builder
     * @param array $options
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ontology', new EntityType($options['doctrineManager']), array(
                    'class' => 'OntoPress\Entity\Ontology',
                    'choice_label' => 'name',
            ))
            ->add('submit', new SubmitCancelType(), array(
                'label' => 'Weiter',
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
        $resolver->setRequired(array(
            'cancel_link',
            'doctrineManager',
        ));

        $resolver->setDefaults(array(
            'attr' => array('class' => 'form-table'),
            'cancel_label' => 'Abbrechen',
        ));
    }

    /**
     * Returns the class Prefix selectOntologyType.
     * @return string "selectOntologyType"
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'selectOntologyType';
    }
}
