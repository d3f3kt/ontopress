<?php

namespace OntoPress\Service;

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
     * Inject dependencies.
     *
     * @param FormFactoryInterface $formFactory Form Factory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
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
                return $this->generateRadioField($field, $builder);
            case OntologyField::TYPE_CHOICE:
                return $this->generateChoiceField($field, $builder);
        }
    }

    private function addTextField(OntologyFIeld $field, FormBuilderInterface $builder)
    {
        return $builder->add($field->getFormFieldName(), 'text', array(
            'label' => $field->getLabel(),
            'required' => $field->getMandatory(),
        ));
    }

    /**
     * Create form builder.
     *
     * @return FormBuilderInterface Form builder
     */
    private function getBuilder()
    {
        return $this->formFactory->createBuilder();
    }
}
