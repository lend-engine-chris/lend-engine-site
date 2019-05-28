<?php

namespace AppBundle\Repository;

/**
 * OrgSiteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrgSiteRepository extends \Doctrine\ORM\EntityRepository
{
    public function search($filters)
    {
        $builder = $this->createQueryBuilder('site');
        $builder->add('select', 'site');
        $builder->leftJoin('site.org', 'org');

        if (isset($filters['search']) && $filters['search']) {
            $builder->andWhere(" site.name LIKE '%{$filters['search']}%' OR org.name LIKE '%{$filters['search']}%' ");
        }

        if (isset($filters['tags']) && $filters['tags']) {
            $sql = '';
            foreach ($filters['tags'] AS $tag) {
                $sql .= " OR org.lends LIKE '{$tag}' ";
            }
            $builder->andWhere("(org.lends = 'none' {$sql})");
        }

        $builder->andWhere("org.status = 'ACTIVE'");

        $builder->setMaxResults(50);

        $query = $builder->getQuery();
        return $query->getResult();
    }
}
