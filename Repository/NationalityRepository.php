<?php

namespace KRG\AddressBundle\Repository;

use Doctrine\ORM\EntityRepository;

class NationalityRepository extends EntityRepository
{
    public function findActives()
    {
        return $this
            ->createQuerybuilder('nationality')
            ->join('nationality.countries', 'countries')
            ->where('countries.active = 1')
            ->getQuery()
            ->getResult();
    }
}
