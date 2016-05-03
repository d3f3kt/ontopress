<?php

namespace OntoPress\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class FormRepository.
 *
 *  provides needed queries for the Dashboard Form Table
 */
class FormRepository extends EntityRepository
{
    /**
     * Count the Elements of a given table.
     *
     * @return number of all Forms
     */
    public function getCount()
    {
        $query = $this->createQueryBuilder('f')
            ->select('COUNT(f)');

        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Sort the form output.
     *
     * @param int    $ontologyId Ontology Id
     * @param string $orderBy    column name
     * @param string $order      asc|desc
     *
     * @return Form[] Form List
     */
    public function getSortedList($ontologyId = null, $orderBy = null, $order = 'ASC')
    {
        $query = $this->createQueryBuilder('f');
        if ($ontologyId) {
            $query->where('f.ontology = :ontologyId')
                ->setParameter('ontologyId', $ontologyId);
        }

        if (in_array($orderBy, array('id', 'name', 'author', 'date'))) {
            if (in_array(strtolower($order), array('asc', 'desc'))) {
                $query->orderBy('f.'.$orderBy, $order);
            }
        }

        return $query->getQuery()->getResult();
    }
}
