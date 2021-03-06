<?php

namespace Application\MiamBundle\Entity;

use Doctrine\ORM\EntityRepository;

class StoryRepository extends EntityRepository
{

    public function findOneByIdWithProject($id)
    {
        return $this->createQueryBuilder('s')
            ->select('s, p')
            ->where('s.id = :id')
            ->innerJoin('s.project', 'p')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    public function findSprintStoriesIndexByProject(Sprint $sprint)
    {
        $qb = $this->createQueryBuilder('s');
        $query = $qb
            ->select('s, p')
            ->where($qb->expr()->in('s.status', array_keys(Story::getExistStatuses())))
            ->andWhere('s.sprint = :sprint OR s.sprint IS NULL')
            ->innerJoin('s.project', 'p')
            ->addOrderBy('p.priority', 'ASC')
            ->addOrderBy('s.priority', 'ASC')
            ->setParameter('sprint', $sprint)
            ->getQuery();

        $stories = $query->execute();

        return $this->storiesToSections($stories);
    }

    /**
     * Get the current sprint hash value
     *
     * @return string the current sprint hash
     **/
    public function getCurrentSprintHash(Sprint $sprint)
    {
        $stories = $this->createQueryBuilder('s')
            ->select('s.updatedAt')
            ->where('s.sprint = :sprint')
            ->orWhere('s.sprint is null')
            ->setParameter('sprint', $sprint)
            ->getQuery()
            ->getScalarResult();
        $hash = '';
        foreach($stories as $story) {
            $hash .= $story['updatedAt'];
        }
        $hash = md5($hash);
        return $hash;
    }

    protected function storiesToSections(array $stories)
    {
        $sections = array();
        foreach($stories as $story) {
            $name = $story->getProject()->getName();
            if(!isset($sections[$name])) {
                $sections[$name] = array(
                    'project' => $story->getProject(),
                    'stories' => array()
                );
            }
            $sections[$name]['stories'][] = $story;
        }

        return $sections;
    }

    public function sort(array $ids)
    {
        foreach($ids as $priority => $id) {
            $this->find($id)->setPriority($priority);
        }
    }

}
