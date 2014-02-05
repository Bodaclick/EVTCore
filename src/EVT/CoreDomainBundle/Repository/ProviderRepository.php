<?php

namespace EVT\CoreDomainBundle\Repository;

use EVT\CoreDomain\Provider\Provider;
use EVT\CoreDomainBundle\Mapping\ProviderMapping;
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
    private $mapper;

    public function setMapper(ProviderMapping $mapper)
    {
        $this->mapper = $mapper;
    }

    public function save($provider)
    {
        if (!$provider instanceof \EVT\CoreDomain\Provider\Provider) {
            throw new \InvalidArgumentException('Wrong object in ProviderRepository');
        }

        $eProvider = $this->mapper->mapDomainToEntity($provider);

        $this->_em->persist($eProvider);
        $this->_em->flush();

        $rflProvider = new \ReflectionClass($provider);
        $rflId = $rflProvider->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($provider, $eProvider->getId());
    }

    public function delete($provider)
    {
    }

    public function update($provider)
    {
    }

    public function findOneById($id)
    {
        return $this->mapper->mapEntityToDomain(parent::findOneById($id));
    }

    /**
     * @param Provider $provider
     * @return null | Provider
     */
    public function findExistingProvider(Provider $provider)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('p')
            ->from('EVTCoreDomainBundle:Provider', 'p')
            ->join('p.genericUser', 'gu')
            ->where('p.slug = :slug');

        $managers = $provider->getManagers();
        $i = 0;
        foreach ($managers as $manager) {
            $i++;
            $qb->andWhere("gu.email = ?$i");
            $qb->setParameter($i, $manager->getEmail());
        }
        $qb->setParameter('slug', $provider->getSlug());

        if (!$eProvider = $qb->getQuery()->getOneOrNullResult()) {
            return null;
        }
        $this->mapper->mapEntityToDomain($eProvider);
    }
}
