<?php

namespace OntoPress\Form\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OntoPress\Form\Base\SubmitCancelType;
use OntoPress\Entity\Ontology;
use OntoPress\Entity\OntologyField;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Type which creates the Ontology Form.
 * It contains n OntologyFields which represent the form fields.
 */
class CreateFormType extends AbstractType
{
    /**
     * Lets the FormBuilderInterface create a form to choose the wanted OntologyFields per Checkboxes.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Formularname: ',
                'attr' => array('class' => 'regular-text'),
            ))
            ->add('ontologyFields', new EntityType($options['doctrineManager']), array(
                'class' => 'OntoPress\Entity\OntologyField',
                'choice_label' => 'label',
                'expanded' => true,
                'multiple' => true,
                'query_builder' => $this->getOntologyFieldsQuery($options['doctrineManager'], $options['ontology']),
            ))
            ->add('submit', new SubmitCancelType(), array(
                'label' => 'Weiter',
                'attr' => array('class' => 'button button-primary'),
                'cancel_link' => $options['cancel_link'],
                'cancel_label' => $options['cancel_label'],
            ));
    }

    /**
     * Build a doctrine query which returns all ontology fields of an ontology.
     *
     * @param ManagerRegistry $doctrineManager DoctrineManager
     * @param Ontology        $ontology        Ontology
     *
     * @return QueryBuilder OntologyFields Query
     */
    public function getOntologyFieldsQuery(ManagerRegistry $doctrineManager, Ontology $ontology)
    {
        $queryBuilder = $doctrineManager->getRepository('OntoPress\Entity\OntologyField')
            ->createQueryBuilder('u');

        return $queryBuilder->join('u.dataOntology', 'd')
            ->join('d.ontology', 'o')
            ->where('u.label is not NULL')
            ->andWhere('u.type not LIKE :type')
            ->andWhere('o.id = :ontoId')
            ->andWhere('d.name not like :knorke')
            ->setParameter('ontoId', $ontology->getId())
            ->setParameter('knorke', '%knorke')
            ->setParameter('type', OntologyField::TYPE_CHOICE)
            ->orderBy('u.label', 'ASC');
    }

    /**
     * Sets the resolver back to default.
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'cancel_link',
            'doctrineManager',
            'ontology',
        ));
        $resolver->setDefaults(array(
            'data_class' => 'OntoPress\Entity\Form',
            'attr' => array('class' => 'form-table'),
            'cancel_label' => 'Abbrechen',
        ));
    }

    /**
     * Returns the createFormType class Prefix.
     *
     * @return string "createFormType"
     */
    public function getName()
    {
        return 'createFormType';
    }
}
