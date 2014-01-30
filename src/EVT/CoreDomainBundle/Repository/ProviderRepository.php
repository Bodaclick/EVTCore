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
        if (!$provider instanceof \EVT\CoreDomainBundle\Entity\Provider) {
            throw new \InvalidArgumentException('Wrong object in LeadRepository');
        }

        $this->_em->persist($provider);
        $this->_em->flush();
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
