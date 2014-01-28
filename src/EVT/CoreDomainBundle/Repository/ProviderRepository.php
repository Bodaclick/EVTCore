<?php

namespace EVT\CoreDomainBundle\Repository;

use EVT\CoreDomain\Provider\ProviderRepositoryInterface as DomainRepository;
use Doctrine\ORM\EntityRepository;

/**
 * ProviderRepository
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class ProviderRepository extends EntityRepository implements DomainRepository
{
    public function save($provider)
    {
    }

    public function delete($provider)
    {
    }

    public function update($provider)
    {
    }

    public function findAll()
    {
    }
}
