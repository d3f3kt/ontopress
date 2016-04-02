<?php

namespace OntePress\Service;

/**
 * Creates Twigcode for all selected Formfields
 *
 * Class TwigGenerator
 * @package OntePress\Service
 */
class TwigGenerator
{
    /**
     * Creates Fieldname, that is needed in the creation function.
     *
     * @param $formField
     * @return string
     */
    private function createFieldName($formField)
    {
        $name = $formField->getForm()->getOntology()->getName() ."_". $formField->getFieldUri();
        return $name;
    }

    /**
     * git status
     *
     * @param $formEntity
     * @return string
     */
    public function creation($formEntity)
    {
        $twigCode = "";
        $formFields = $formEntity->getFormFields();
        foreach ($formFields as $formField) {
            $fieldName = createFieldName($formField);
            $twigCode .= "{{form_label(form." . $fieldName . ")}}"
                . "{{form_errors(form." . $fieldName . ")}}"
                . "{{form_widget(form." .$fieldName . ")}}";
        }

        return $twigCode;
    }
}
