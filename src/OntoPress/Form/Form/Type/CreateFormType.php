<?php

namespace OntoPress\Form\Form\Type;

use OntoPress\Service\DoctrineManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OntoPress\Form\Base\SubmitCancelType;

class CreateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ontology', new EntityType($options['doctrineManager']), array(
                    'class' => 'OntoPress\Entity\Ontology',
                    'choice_label' => 'name',
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
        $resolver->setRequired(array(
            'cancel_link',
            'doctrineManager',
            ));
        $resolver->setDefaults(array(
            'data_class' => 'OntoPress\Entity\Form',
            'attr' => array('class' => 'form-table'),
            'cancel_label' => 'Abbrechen',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'formCreateType';
    }
}
