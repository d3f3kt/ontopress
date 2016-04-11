<?php

namespace OntoPress\Service;

use OntoPress\Entity\OntologyField;
use Doctrine\ORM\EntityManager;

class RestrictionHelper
{
    /**
     * Doctrine Entity Manager.
     *
     * @var EntityManager
     */
    private $doctrine;

    /**
     * Dependency Injection
     * @param EntityManager $doctrine Doctrine Entity Manager
     */
    public function __construct(EntityManager $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param OntologyField $field
     *
     * @return array
     */
    public function getChoices(OntologyField $field)
    {
        $choiceArray = array();

        $repo = $this->doctrine->getRepository('OntoPress\Entity\OntologyField');
        foreach ($field->getRestrictions() as $restriction) {
            $choiceArray[$restriction->getId()] = $repo->findOneByName($restriction->getName())
                ->getLabel();
        }

        return $choiceArray;
    }
}
