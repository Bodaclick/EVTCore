<?php

namespace EVT\CoreDomainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use EVT\CoreDomain\Provider\VerticalRepositoryInterface as DomainRepository;
use EVT\CoreDomainBundle\Mapping\VerticalMapping;

class VerticalRepository extends EntityRepository implements DomainRepository
{
    private $mapping;

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
        return $this->mapping->mapEntityToDomain(parent::findOneByDomain($domain));
    }

    public function setMapper(VerticalMapping $mapping)
    {
        $this->mapping = $mapping;
    }
}
