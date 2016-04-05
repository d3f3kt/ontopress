<?php

namespace OntoPress\Service;

use OntoPress\Entity\Form;
use OntoPress\Entity\OntologyField;

/**
 * To make it more comfortable to customize forms, this class generates a base Twig
 * structure which contains all fields of a given OntoPressForm.
 */
class TwigGenerator
{
    /**
     * Generate a Twig structure of an OntoPressForm and return it as string.
     *
     * @param Form $form
     *
     * @return string
     */
    public function generate(Form $form)
    {
        $twigCode = '';
        $formFields = $form->getOntologyFields();
        foreach ($formFields as $formField) {
            $fieldName = $this->createFieldName($formField);
            $twigCode .= '{{form_label(form.'.$fieldName.")}}\n"
                .'{{form_errors(form.'.$fieldName.")}}\n"
                .'{{form_widget(form.'.$fieldName.")}}\n\n";
        }

        return $twigCode;
    }

    /**
     * Helper function to generate unique name from ontology field object.
     *
     * @param OntologyField $formField
     *
     * @return string
     */
    private function createFieldName($formField)
    {
        $name = $formField->getDataOntology()->getOntology()->getName()
            .'_'
            .$formField->getName();
    }
}
