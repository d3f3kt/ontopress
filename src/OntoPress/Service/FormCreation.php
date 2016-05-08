<?php

namespace OntoPress\Service;

use Symfony\Component\Form\Form as SymForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormBuilderInterface;
use OntoPress\Entity\Form as OntoForm;
use OntoPress\Entity\OntologyField;
use Symfony\Component\Validator\Constraints;

/**
 * Class FormCreation.
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
     * @param FormFactoryInterface $formFactory       Form Factory
     * @param RestrictionHelper    $restrictionHelper RestrictionHelper
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
    public function create(OntoForm $ontoForm, $formData = null)
    {
        $builder = $this->getBuilder();

        $builder->add('OntologyField_', 'text', array(
            'label' => 'Ressourcenname',
            'required' => true,
            'data' => $formData['OntoPress:name'],
        ));
        foreach ($ontoForm->getOntologyFields() as $field) {
            $this->addField($field, $builder);
        }
        $builder->setData($formData);

        return $builder->getForm();
    }

    /**
     * Get all constraints
     *
     * @param OntologyField $field
     *
     * @return array
     */
    private function getConstraints(OntologyField $field)
    {
        $constraints = array();

        if ($field->getMandatory()) {
            $constraints[] = new Constraints\NotBlank();
        }
        if ($regex = $field->getRegex()) {
            $constraints[] = new Constraints\Regex(array(
                'pattern' => $regex,
            ));
        }

        return $constraints;
    }

    /**
     * Builds every needed field for the properties
     *
     * @param OntologyField $field
     * @param FormBuilderInterface $builder
     *
     * @return mixed
     */
    private function addField(OntologyField $field, FormBuilderInterface $builder)
    {
        switch ($field->getType()) {
            case OntologyField::TYPE_TEXT:
                return $this->addTextField($field, $builder);
            case OntologyField::TYPE_RADIO:
                return $this->addRadioField($field, $builder);
            case OntologyField::TYPE_SELECT:
                return $this->addChoiceField($field, $builder);
        }
    }

    /**
     * Creates a text field
     *
     * @param OntologyField $field
     * @param FormBuilderInterface $builder
     *
     * @return mixed
     */
    private function addTextField(OntologyField $field, FormBuilderInterface $builder)
    {
        return $builder->add($field->getFormFieldName(), 'text', array(
            'label' => $field->getLabel(),
            'required' => $field->getMandatory(),
            'constraints' => $this->getConstraints($field),
        ));
    }

    /**
     * Creates a radio button field
     *
     * @param OntologyField $field
     * @param FormBuilderInterface $builder
     *
     * @return mixed
     */
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

    /**
     * Creates a field for choices
     *
     * @param OntologyField $field
     * @param FormBuilderInterface $builder
     *
     * @return mixed
     */
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
