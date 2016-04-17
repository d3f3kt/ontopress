<?php

namespace OntoPress\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form as SymForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormBuilderInterface;
use OntoPress\Entity\Form as OntoForm;
use OntoPress\Entity\OntologyField;

/**
 * Service to generate a SymfonyForm from an OntoPressForm.
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
     * Inject dependencies.
     *
     * @param FormFactoryInterface $formFactory Form Factory
     * @param EntityManager        $doctrine    Doctrine Entity Manager
     */
    public function __construct(FormFactoryInterface $formFactory, RestrictionHelper $restrictionHelper)
    {
        $this->formFactory = $formFactory;
        $this->restrictionHelper = $restrictionHelper;
    }

    /**
     * Iterate through all OntoPressFormFields and add them to the Symfony Form.
     *
     * @param OntoForm $ontoForm OntoPress From
     *
     * @return SymForm SymfonyForm
     */
    public function create(OntoForm $ontoForm)
    {
        $builder = $this->getBuilder();
        foreach ($ontoForm->getOntologyFields() as $field) {
            $this->addField($field, $builder);
        }

        return $builder->getForm();
    }

    private function addField(OntologyField $field, FormBuilderInterface $builder)
    {
        switch ($field->getType()) {
            case OntologyField::TYPE_TEXT:
                return $this->addTextField($field, $builder);
            case OntologyField::TYPE_RADIO:
                return $this->addRadioField($field, $builder);
            case OntologyField::TYPE_CHOICE:
                return $this->addChoiceField($field, $builder);
        }
    }

    private function addTextField(OntologyField $field, FormBuilderInterface $builder)
    {
        return $builder->add($field->getFormFieldName(), 'text', array(
            'label' => $field->getLabel(),
            'required' => $field->getMandatory(),
        ));
    }

    private function addRadioField(OntologyField $field, FormBuilderInterface $builder)
    {
        return $builder->add($field->getFormFieldName(), 'choice', array(
            'label' => $field->getLabel(),
            'required' => $field->getMandatory(),
            'multiple' => false,
            'expanded' => true,
            'placeholder' => false,
            'choices' => $this->restrictionHelper->getChoices($field),
        ));
    }

    private function addChoiceField(OntologyField $field, FormBuilderInterface $builder)
    {
        return $builder->add($field->getFormFieldName(), 'choice', array(
            'label' => $field->getLabel(),
            'required' => $field->getMandatory(),
            'multiple' => false,
            'expanded' => false,
            'placeholder' => false,
            'choices' => $this->restrictionHelper->getChoices($field),
        ));
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
}
