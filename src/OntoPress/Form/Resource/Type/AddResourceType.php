<?php

namespace OntoPress\Form\Resource\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OntoPress\Form\Base\SubmitCancelType;
use Doctrine\Common\Collections;

/**
 * Show for each Ontology all Forms in select choice type.
 */
class AddResourceType extends AbstractType
{
    /**
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('form', 'choice', ['choices' => $this->generateChoices($options)])
            ->add('submit', new SubmitCancelType(), array(
                'label' => 'Weiter',
                'attr' => array('class' => 'button button-primary'),
                'cancel_link' => $options['cancel_link'],
                'cancel_label' => $options['cancel_label'],
            ));
    }

    public function generateChoices($options)
    {
        $ontologyArray = array();
        foreach ($options['ontologies'] as $key => $ontology) {
            $formArray = array();
            foreach ($ontology->getOntologyForms() as $form) {
                $formArray[$form->getId()] = $form->getName();
            }
            $ontologyArray[$ontology->getName()] = $formArray;
        }
        
        return $ontologyArray;
    }

    /**
     * Sets the resolver to default
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'cancel_link',
            'doctrineManager',
            'ontologies'
        ));

        $resolver->setDefaults(array(
            'attr' => array('class' => 'form-table'),
            'cancel_label' => 'Abbrechen',
        ));
    }

    /**
     * Getter Name
     *
     * @return string "networkMessage"
     */
    public function getName()
    {
        return 'addResourceType';
    }
}
