<?php

namespace OntoPress\Service;

use OntoPress\Entity\OntologyField;
use Doctrine\ORM\EntityManager;
/**
 * Class RestrictionHelper
 *
 * The RestrictionHelper helps to get all Restrictions of an OntologyField
 */
class RestrictionHelper
{
    /**
     * Doctrine Entity Manager.
     *
     * @var EntityManager
     */
    private $doctrine;

    /**
     * The Constructor is automatically called by creating a new RestrictionHelper.
     * It initializes a doctrine instance with the given parameter.
     *
     * @param EntityManager $doctrine Doctrine Entity Manager
     */
    public function __construct(EntityManager $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Get all Restrictions as Array of Choices of an OntologyField.
     *
     * @param OntologyField $field
     *
     * @return array All Choices
     */
    public function getChoices(OntologyField $field)
    {
        $choiceArray = array();

        $repo = $this->doctrine->getRepository('OntoPress\Entity\OntologyField');
        foreach ($field->getRestrictions() as $restriction) {
            if ($repo->findOneByName($restriction->getName()) != null) {
                $choiceArray[$restriction->getId()] = $repo->findOneByName($restriction->getName())
                ->getLabel();
            }
        }

        return $choiceArray;
    }
}
