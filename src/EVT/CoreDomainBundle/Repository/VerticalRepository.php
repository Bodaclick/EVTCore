<?php

namespace EVT\CoreDomainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use EVT\CoreDomain\Provider\VerticalRepositoryInterface as DomainRepository;
use Doctrine\ORM\EntityRepository;

class VerticalRepository extends EntityRepository implements DomainRepository
{
    public function save($showroom)
    {
    }

    public function delete($showroom)
    {
    }

    public function update($showroom)
    {
    }

    public function findAll()
    {
    }

    public function findOneByDomain($domain)
    {
    }

}

