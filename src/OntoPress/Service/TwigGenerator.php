<?php

namespace OntoPress\Service;

use OntoPress\Entity\Form;
use OntoPress\Entity\OntologyField;

/**
 * Class TwigGenerator
 *
 * To make customizing forms more comfortable, this class generates a base Twig
 * structure which contains all OntologyFields of a given OntoPressForm.
 */
class TwigGenerator
{
    /**
     * Twig Environment to render form structure.
     *
     * @var Twig_Environment
     */
    private $twig;

    /**
     * Restriction Helper to get choices.
     *
     * @var RestrictionHelper
     */
    private $restrictionHelper;

    /**
     * Inject dependencies.
     *
     * @param \Twig_Environment $twig              Twig Environment to render templates
     * @param RestrictionHelper $restrictionHelper Restriction Helper
     */
    public function __construct(\Twig_Environment $twig, RestrictionHelper $restrictionHelper)
    {
        $this->twig = $twig;
        $this->restrictionHelper = $restrictionHelper;
    }

    /**
     * Generate a Twig structure of an OntoPressForm and return it as string.
     *
     * @param Form $form
     *
     * @return string
     */
    public function generate(Form $form)
    {
        $twigFields = array();

        $nameField = new OntologyField();
        $nameField->setName('Name')
            ->setLabel('Name')
            ->setType(OntologyField::TYPE_TEXT)
            ->setMandatory(true)
            ->setPossessed(true);
        $twigFields[] = $this->generateFormField($nameField);
        
        foreach ($form->getOntologyFields() as $ontologyField) {
            $twigFields[] = $this->generateFormField($ontologyField);
        }

        return $this->twig->render('formCreation/form.html.twig', array(
            'formFields' => $twigFields,
        ));
    }

    /**
     * Decide which form field typ should be rendered.
     *
     * @param OntologyField $ontologyField Ontology Field
     *
     * @return array Form Field array
     */
    private function generateFormField(OntologyField $ontologyField)
    {
        switch ($ontologyField->getType()) {
            case OntologyField::TYPE_TEXT:
                return $this->generateTextField($ontologyField);
            case OntologyField::TYPE_RADIO:
                return $this->generateRadioField($ontologyField);
            case OntologyField::TYPE_SELECT:
                return $this->generateChoiceField($ontologyField);
        }
    }

    /**
     * Generate Text Field.
     *
     * @param OntologyField $ontologyField Ontology Field
     *
     * @return array array of form field information
     */
    private function generateTextField(OntologyField $ontologyField)
    {
        $twigField = array(
            'label' => $this->generateLabel($ontologyField),
            'error' => '{{form_errors(form.'.$ontologyField->getFormFieldName().')}}',
            'type' => 'text',
            'widget' => array(
                'val' => $this->generateFieldValue($ontologyField),
                'attr' => $this->generateFieldAttributes($ontologyField),
            ),
        );

        return $twigField;
    }

    /**
     * Generate Radio Field.
     *
     * @param OntologyField $ontologyField Ontology Field
     *
     * @return array array of form field information
     */
    private function generateRadioField(OntologyField $ontologyField)
    {
        $twigField = array(
            'label' => $this->generateLabel($ontologyField),
            'error' => '{{form_errors(form.'.$ontologyField->getFormFieldName().')}}',
            'type' => 'radio',
            'widget' => array(
                'choices' => $this->restrictionHelper->getChoices($ontologyField),
                'attr' => $this->generateChoiceAttributes($ontologyField),
                'labelAttr' => 'for="OntoPressForm_'.$ontologyField->getFormFieldName().'_%id%"',
            ),
        );

        return $twigField;
    }

    /**
     * TODO: maybe implement select field.
     *
     * @param OntologyField $ontologyField Ontology Field
     *
     * @return array array of form field information
     */
    private function generateChoiceField(OntologyField $ontologyField)
    {
        $twigField = array(
            'label' => $this->generateLabel($ontologyField),
            'error' => '{{form_errors(form.'.$ontologyField->getFormFieldName().')}}',
            'type' => 'select',
            'widget' => array(
                'choices' => $this->restrictionHelper->getChoices($ontologyField),
                'attr' => $this->generateFieldAttributes($ontologyField),
                'labelAttr' => 'for="OntoPressForm_'.$ontologyField->getFormFieldName().'_%id%"',
            ),
        );

        return $twigField;
    }

    /**
     * Generate label for form field.
     *
     * @param OntologyField $ontologyField Ontology Field
     *
     * @return array array of label information
     */
    private function generateLabel(OntologyField $ontologyField)
    {
        $twigCode = '<label%s%s>%s</label>';

        switch ($ontologyField->getType()) {
            case OntologyField::TYPE_TEXT:
                return sprintf(
                    $twigCode,
                    " for='OntoPressForm_".$ontologyField->getFormFieldName()."'",
                    $ontologyField->getMandatory() ? ' class="required"' : '',
                    $ontologyField->getLabel()
                );
            default:
                return sprintf(
                    $twigCode,
                    '',
                    $ontologyField->getMandatory() ? ' class="required"' : '',
                    $ontologyField->getLabel()
                );
        }
    }

    /**
     * Generate value tag for text field.
     *
     * @param OntologyField $ontologyField Ontology Field
     *
     * @return string value tag of text field
     */
    private function generateFieldValue(OntologyField $ontologyField)
    {
        return '{{ '.$this->createFieldVarName($ontologyField).'.value is not empty ? '
            ."'value=\"' ~ ".$this->createFieldVarName($ontologyField).'.value }}';
    }

    /**
     * Generate name and id tag for form field.
     *
     * @param OntologyField $ontologyField Ontology Field
     *
     * @return string name, id tag of text field
     */
    private function generateFieldAttributes(OntologyField $ontologyField)
    {
        return 'id="OntoPressForm_'.$ontologyField->getFormFieldName().'" '.
            'name="OntoPressForm['.$ontologyField->getFormFieldName().']"';
    }

    /**
     * Generate name and id tag for radio field.
     *
     * @param OntologyField $ontologyField Ontology Field
     *
     * @return string name, id tag of radio field
     */
    private function generateChoiceAttributes(OntologyField $ontologyField)
    {
        return 'id="OntoPressForm_'.$ontologyField->getFormFieldName().'_%id%" '.
            'name="OntoPressForm['.$ontologyField->getFormFieldName().']"';
    }

    /**
     * Create vars name for ontology field.
     *
     * @param OntologyField $ontologyField Ontology Field
     *
     * @return string field vars name
     */
    private function createFieldVarName(OntologyField $ontologyField)
    {
        return 'form.children.'.$ontologyField->getFormFieldName().'.vars';
    }
}
