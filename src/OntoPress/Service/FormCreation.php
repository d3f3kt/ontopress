<?php

namespace OntoPress\Service;

use Doctrine\DBAL\Query\QueryBuilder;
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
                return $this->addRadioField($field, $builder);
            case OntologyField::TYPE_CHOICE:
                return $this->generateChoiceField($field, $builder);
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
            'choices' => array(), //TODO: get choices of radio button field
        ));
    }

    private function addChoiceField(OntologyField $field, FormBuilderInterface $builder)
    {
        return $builder->add($field->getFormFieldName(), 'choice', array(
            'label' => $field->getLabel(),
            'required' => $field->getMandatory(),
            'multiple' => false,
            'expanded' => false,
            'choices' => array(), //TODO. get choices of choice field
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

    /**
     * @param OntologyField $field
     * @return array
     */
    private function getChoiceArray(OntologyField $field)
    {
        $choiceArray = array();
        $entityManager = $this->get('ontopress.doctrine_manager');
        $qb = $entityManager->createQueryBuilder();
        foreach ($field->getRestrictions()->toArray() as $key => $choice) {
            $pushChoice = $qb->select('u')
                ->from('OntologyField')
                ->where('u.name = :name')
                ->setParameter('name', $choice->getName());
            $choiceArray[$choice->getName()] = $pushChoice;
        }
        return $choiceArray;
    }
}
