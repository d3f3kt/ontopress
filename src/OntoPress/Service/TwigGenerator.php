<?php

namespace OntoPress\Service;

use OntoPress\Entity\Form;
use OntoPress\Entity\OntologyField;
use OntoPress\Service\OntologyParser\Parser;

/**
 * To make it more comfortable to customize forms, this class generates a base Twig
 * structure which contains all fields of a given OntoPressForm.
 */
class TwigGenerator
{
    /**
     * Ontology Parser.
     *
     * @var Parser
     */
    private $parser;

    /**
     * Inject dependencies.
     *
     * @param Parser $parser OntologyParser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
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
        $twigCode = $this->generateFormStart();

        $ontologyFields = $form->getOntologyFields();
        foreach ($ontologyFields as $ontologyField) {
            $twigCode .= $this->generateFormField($ontologyField);
        }

        $twigCode .= $this->generateFormEnd();

        return $twigCode;
    }

    private function generateFormField(OntologyField $ontologyField)
    {
        switch ($ontologyField->getType()) {
            case OntologyField::TYPE_TEXT:
                return $this->generateTextField($ontologyField);
            case OntologyField::TYPE_RADIO:
                return $this->generateRadioField($ontologyField);
            case OntologyField::TYPE_CHOICE:
                return $this->generateChoiceField($ontologyField);
        }
    }

    private function generateTextField(OntologyField $ontologyField)
    {
        //TODO
        $fieldName = $ontologyField->getFormFieldName();
        $twigCode .= '{{form_label(form.'.$ontologyField->getFormFieldName().")}}\n";
        //    .'{{form_errors(form.'.$ontologyField->getFormFieldName().")}}\n";
        $twigCode .= "<input type='text' "
            .$this->generateFieldValue($ontologyField).' '
            .$this->generateFieldAttributes($ontologyField)
            ." />\n\n";

        return $twigCode;
    }

    private function generateRadioField(OntologyField $ontologyField)
    {
        //TODO
        $fieldName = $formField->getFormFieldName();
        $twigCode .= '{{form_label(form.'.$fieldName.")}}\n"
            .'{{form_errors(form.'.$fieldName.")}}\n"
            .'{{form_widget(form.'.$fieldName.")}}\n\n";

        return $twigCode;
    }

    private function generateChoiceField(OntologyField $ontologyField)
    {
        //TODO
        $fieldName = $this->createFieldName($formField);
        $twigCode .= '{{form_label(form.'.$fieldName.")}}\n"
            .'{{form_errors(form.'.$fieldName.")}}\n"
            .'{{form_widget(form.'.$fieldName.")}}\n\n";

        return $twigCode;
    }

    private function generateFieldValue(OntologyField $ontologyField)
    {
        return '{{ '.$this->createFieldVarName($ontologyField).'.value is not empty ? '
            ."'value=\"' ~ ".$this->createFieldVarName($ontologyField).' }}';
    }

    private function generateFieldAttributes(OntologyField $ontologyField)
    {
        return 'id="{{ '.$this->createFieldVarName($ontologyField).'.id }}" '.
            'name="{{ '.$this->createFieldVarName($ontologyField).'.name }}"';
    }

    /**
     * Generates HTML which contains the form head.
     *
     * @return string HTML
     */
    private function generateFormStart()
    {
        return '<form'
            ." name='{{ form.vars.name }}'"
            ." method='{{ form.vars.method }}'"
            ." action='{{ form.vars.action }}'"
            ." enctype='multipart/form-data'> \n\n";
    }

    /**
     * Generates HTML which contains the closing form tag.
     *
     * @return string HTML
     */
    private function generateFormEnd()
    {
        return '</form>';
    }

    private function createFieldVarName(OntologyField $ontologyField)
    {
        return 'form.children.'.$ontologyField->getFormFieldName().'.vars';
    }
}
