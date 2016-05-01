<?php

namespace OntoPress\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form as SymForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormBuilderInterface;
use OntoPress\Form\Base\SubmitCancelType;
use OntoPress\Entity\Form as OntoForm;
use OntoPress\Entity\OntologyField;

/**
 * Class FormCreation
 *
 * Service to generate a SymfonyForm of an OntoPressForm.
 */
class FormCreation
{
    /**
     * Form Factors to build form.
     *
     * @var FormFactoryInterface;
     */
    private $formFactory;

    /**
     * Restriction helper to get choices.
     *
     * @var RestrictionHelper
     */
    private $restrictionHelper;

    /**
     * The Constructor is automatically called by creating a new FormCreation.
     * It initializes the formFactory and restrictionHelper with the given parameters.
     *
     * @param FormFactoryInterface $formFactory Form Factory
     *
     * @param RestrictionHelper        $restrictionHelper    RestrictionHelper
     */
    public function __construct(FormFactoryInterface $formFactory, RestrictionHelper $restrictionHelper)
    {
        $this->formFactory = $formFactory;
        $this->restrictionHelper = $restrictionHelper;
    }

    /**
     * Iterate through all OntoPressFormFields of the given OntoForm
     * and create a Symfony Form including all OntoPressFormFields.
     *
     * @param OntoForm $ontoForm OntoPress From
     *
     * @return SymForm SymfonyForm
     */
    public function create(OntoForm $ontoForm)
    {
        $builder = $this->getBuilder();

        $builder->add('OntologyField_', 'text', array(
            'label' => 'Ressourcenname',
            'required' => true,
        ));

        foreach ($ontoForm->getOntologyFields() as $field) {
            $this->addField($field, $builder);
        }

        return $builder->getForm();
    }

    private function addField(OntologyField $field, FormBuilderInterface $builder, $value = null)
    {
        switch ($field->getType()) {
            case OntologyField::TYPE_TEXT:
                return $this->addTextField($field, $builder, $value);
            case OntologyField::TYPE_RADIO:
                return $this->addRadioField($field, $builder, $value);
            case OntologyField::TYPE_SELECT:
                return $this->addChoiceField($field, $builder, $value);
        }
    }

    private function addTextField(OntologyField $field, FormBuilderInterface $builder, $value)
    {
        if (!$value) {
            return $builder->add($field->getFormFieldName(), 'text', array(
                'label' => $field->getLabel(),
                'required' => $field->getMandatory(),
            ));
        } else {
            return $builder->add($field->getFormFieldName(), 'text', array(
                'label' => $field->getLabel(),
                'required' => $field->getMandatory(),
                'data' => $value,
            ));
        }
    }

    private function addRadioField(OntologyField $field, FormBuilderInterface $builder, $value)
    {
        if (!$value) {
            return $builder->add($field->getFormFieldName(), 'choice', array(
                'label' => $field->getLabel(),
                'required' => $field->getMandatory(),
                'multiple' => false,
                'expanded' => true,
                'placeholder' => false,
                'choices' => $this->restrictionHelper->getChoices($field),
            ));
        } else {
            return $builder->add($field->getFormFieldName(), 'choice', array(
                'label' => $field->getLabel(),
                'required' => $field->getMandatory(),
                'multiple' => false,
                'expanded' => true,
                'placeholder' => false,
                'choices' => $this->restrictionHelper->getChoices($field),
                'data' => $value,
            ));
        }
    }

    private function addChoiceField(OntologyField $field, FormBuilderInterface $builder, $value)
    {
        if (!$value) {
            return $builder->add($field->getFormFieldName(), 'choice', array(
                'label' => $field->getLabel(),
                'required' => $field->getMandatory(),
                'multiple' => false,
                'expanded' => false,
                'placeholder' => false,
                'choices' => $this->restrictionHelper->getChoices($field),
            ));
        } else {
            return $builder->add($field->getFormFieldName(), 'choice', array(
                'label' => $field->getLabel(),
                'required' => $field->getMandatory(),
                'multiple' => false,
                'expanded' => false,
                'placeholder' => false,
                'choices' => $this->restrictionHelper->getChoices($field),
                'data' => $value,
            ));
        }
    }

    /**
     * Create form builder.
     *
     * @return FormBuilderInterface Form builder
     */
    private function getBuilder()
    {
        return $this->formFactory->createNamedBuilder('OntoPressForm', 'form', null, array(
            'block_name' => 'OntoPressForm',
        ));
    }

    public function createFilledForm(OntoForm $form, $formData)
    {
        $builder = $this->getBuilder();

        $builder->add('OntologyField_', 'text', array(
            'label' => 'Ressourcenname',
            'required' => true,
            'data' => $formData['OntoPress:name']
        ));

        foreach ($form->getOntologyFields() as $field) {
            $value = $formData[$field->getName()];
            $this->addField($field, $builder, $value);
        }

        return $builder->getForm();
    }
}
