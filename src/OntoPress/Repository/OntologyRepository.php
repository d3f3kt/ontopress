<?php

namespace OntoPress\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class OntologyRepository
 *
 * provides needed queries for the Dashboard Ontology Table
 */
class OntologyRepository extends EntityRepository
{
    /**
     * Count the Elements of a given table
     *
     * @return number of all Ontologies
     */
    public function getCount()
    {
        $query = $this->createQueryBuilder('o')
            ->select('COUNT(o)');

        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Searches the 5 most used Ontologies in the database via the number of their forms
     *
     * @return table with the 5 most used Ontologies
     */
    public function getMostUsedOntologies()
    {
        $query = $this->createQueryBuilder('o')
            ->addSelect('COUNT(f) AS HIDDEN formCount')
            ->leftJoin('o.ontologyForms', 'f')
            ->groupBy('f')
            ->setMaxResults(5)
            ->orderBy('formCount', 'DESC');

        return $query->getQuery()->getResult();
    }
}
