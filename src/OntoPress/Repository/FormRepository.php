<?php

namespace OntoPress\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class FormRepository
 * 
 *  provides needed queries for the Dashboard Form Table
 */
class FormRepository extends EntityRepository
{
    /**
     * Count the Elements of a given table
     * 
     * @return number of all Forms
     */
    public function getCount()
    {
        $query = $this->createQueryBuilder('f')
            ->select('COUNT(f)');

        return $query->getQuery()->getSingleScalarResult();
    }
}
