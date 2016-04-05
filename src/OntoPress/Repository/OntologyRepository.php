<?php

namespace OntoPress\Repository;

use Doctrine\ORM\EntityRepository;

class OntologyRepository extends EntityRepository
{
    public function getCount()
    {
        $query = $this->createQueryBuilder('o')
            ->select('COUNT(o)');

        return $query->getQuery()->getSingleScalarResult();
    }
    
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
