<?php

namespace OntoPress\Form\Resource\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OntoPress\Form\Base\SubmitCancelType;

/**
 * That is an example form.
 */
class AddResourceDetailType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*
        $formFields = $options['data']->getOntologyFields();
        foreach ($formFields as $field) {
            $builder
                -> add($field->getFieldUri(), checkType($field), array(
                    'label' => $field->getName(),
                    'required' => $field->getMandatory(),
                    'attr' => calculateAttribute($field),
                ));
        }*/

        $builder
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
        $resolver->setRequired('cancel_link');
        $resolver->setDefaults(array(
            'attr' => array('class' => 'form-table'),
            'cancel_label' => 'Abbrechen',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'networkMessage';
    }
/*
    private function checkType($field)
    {
        $type = $field->getType();
        if ($type == OntologyNode::TYPE_TEXT) {
            return 'text';
        } else if ($type == OntologyNode::TYPE_RADIO) {
            return ChoiceType::class;
        }

        //default return value
        return 'error';
    }

    private function calculateAttribute($field)
    {
        $type = $field->getType();
        if ($type == OntologyNode::TYPE_TEXT) {
            return 'regular-text';
        } else if ($type == OntologyNode::TYPE_RADIO) {
            return  array('choices' => array($field->calculateChoices() => true, $field->calculateChoices() => false));
        }
        //default return value
        return 'error';
    }

    private function calculateChoices()
    {

    }*/
}
