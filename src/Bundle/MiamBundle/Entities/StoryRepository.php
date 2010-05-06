<?php

namespace Bundle\MiamBundle\Entities;

use Doctrine\ORM\EntityRepository;

/**
 * StoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StoryRepository extends EntityRepository
{

    public function findAllOrderByPriority()
    {
        return $this->createQueryBuilder('e')
        ->orderBy('e.priority', 'asc')
        ->getQuery()
        ->execute();
    }

}