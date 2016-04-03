<?php

namespace OntoPress\Service;

use OntoPressEntity\Form;
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
    public function creation(Form $form)
    {
        $twigCode = '';
        $formFields = $form->getFormFields();
        foreach ($formFields as $formField) {
            $fieldName = createFieldName($formField);
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
        return $formField->getForm()->getOntology()->getName().'_'.$formField->getFieldUri();
    }
}
