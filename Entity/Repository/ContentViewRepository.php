<?php
/*
 * This file is a part of cms project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 8:08 PM 6/30/15
 */

namespace Youshido\CMSBundle\Entity\Repository;


use Doctrine\ORM\EntityRepository;

class ContentViewRepository extends EntityRepository
{
    public function loadSourceList()
    {
        $builder = $this->createQueryBuilder('cv')
            ->where('cv.parent IS NULL');

        return $builder
                ->getQuery()
                ->getResult();
    }
}