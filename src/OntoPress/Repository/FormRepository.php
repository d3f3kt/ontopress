<?php

namespace OntoPress\Repository;

use Doctrine\ORM\EntityRepository;

class FormRepository extends EntityRepository
{
    public function getCount()
    {
        $query = $this->createQueryBuilder('f')
            ->select('COUNT(f)');

        return $query->getQuery()->getSingleScalarResult();
    }
}
