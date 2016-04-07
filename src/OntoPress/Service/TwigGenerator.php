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
        $twigCode .= "<table class='form-table' id='OntoPressForm'>\n";

        foreach ($form->getOntologyFields() as $ontologyField) {
            $twigCode .= "\t<tr>\n";
            $twigCode .= $this->generateFormField($ontologyField);
            $twigCode .= "\t</tr>\n";
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
        $twigCode = $this->generateLabel($ontologyField);
        $twigCode .= '{{form_errors(form.'.$ontologyField->getFormFieldName().")}}\n";
        $twigCode .= "<input type='text' "
            .$this->generateFieldValue($ontologyField).' '
            .$this->generateFieldAttributes($ontologyField)
            ." />\n\n";

        return $twigCode;
    }

    private function generateRadioField(OntologyField $ontologyField)
    {
        //TODO
        $twigCode = $this->generateLabel($ontologyField)."\n";
        $twigCode .= '{{form_errors(form.'.$ontologyField->getFormFieldName().")}}\n"
            .'{{form_widget(form.'.$ontologyField->getFormFieldName().")}}\n\n";

        return $twigCode;
    }

    private function generateChoiceField(OntologyField $ontologyField)
    {
        //TODO
        $twigCode .= '{{form_label(form.'.$ontologyField->getFormFieldName().")}}\n"
            .'{{form_errors(form.'.$ontologyField->getFormFieldName().")}}\n"
            .'{{form_widget(form.'.$ontologyField->getFormFieldName().")}}\n\n";

        return $twigCode;
    }

    private function generateLabel(OntologyField $ontologyField)
    {
        $twigCode = "\t\t<th scope='row'>\n"
            ."\t\t\t<label%s%s>%s</label>\n"
            ."\t\t</th>";

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

    private function generateFieldValue(OntologyField $ontologyField)
    {
        return '{{ '.$this->createFieldVarName($ontologyField).'.value is not empty ? '
            ."'value=\"' ~ ".$this->createFieldVarName($ontologyField).'.value }}';
    }

    private function generateFieldAttributes(OntologyField $ontologyField)
    {
        return 'id="OntoPressForm_'.$ontologyField->getFormFieldName().'" '.
            'name="OntoPressForm['.$ontologyField->getFormFieldName().']"';
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
            ." enctype='multipart/form-data'>\n";
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
