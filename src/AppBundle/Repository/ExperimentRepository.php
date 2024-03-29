<?php

namespace AppBundle\Repository;

use AppBundle\Traits\RepositoryPaginatorTrait;

/**
 * ExperimentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ExperimentRepository extends \Doctrine\ORM\EntityRepository
{
    use RepositoryPaginatorTrait;

    /**
     * @param $filters
     * @param int $currentPage
     * @param int $perPage
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function getAvailableExperiments($filters, $currentPage = 1, $perPage = 20)
    {
        $qb = $this->createQueryBuilder('ex');

        $qb->select('ex');

        $qb->where($qb->expr()->isNull('ex.deleted'));

        if (!empty($filters['operator'])) {
            $qb
                ->andWhere(
                    $qb->expr()->in('ex.operator', ':operator')
                )
                ->setParameter('operator', $filters['operator'])
            ;
        }

        if (!empty($filters['scene'])) {
            $qb
                ->andWhere(
                    $qb->expr()->in('ex.scene', ':scene')
                )
                ->setParameter('scene', $filters['scene'])
            ;
        }

        $qb->orderBy('ex.id','DESC');

        $paginator = $this->paginate($qb, $currentPage, $perPage);
        return $paginator;
    }
}