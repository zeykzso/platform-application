<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\Repository;

use Doctrine\ORM\EntityRepository;

class IssueRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getIssuesByStatusData()
    {
        $qb= $this->createQueryBuilder('i')
            ->select('s.name as statusName, COUNT(i.id) as nrOfIssues')
            ->leftJoin('i.status', 's')
            ->groupBy('s.id');

        return $qb->getQuery()->getResult();
    }
}
