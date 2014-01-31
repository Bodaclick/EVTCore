<?php

namespace EVT\CoreDomainBundle\Mapping;

use Doctrine\ORM\EntityManager;
use EVT\CoreDomain\Provider\Provider;
use \EVT\CoreDomainBundle\Entity\Provider as EntityProvider;

/**
 * ProviderMapping
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class ProviderMapping
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function mapDomainToEntity(Provider $provider)
    {
        $eProvider = new EntityProvider();

        if (null !== $provider->getId()) {
            $rflProvider = new \ReflectionClass($eProvider);
            $rflId = $rflProvider->getProperty('id');
            $rflId->setAccessible(true);
            $rflId->setValue($eProvider, $provider->getId());
        }

        $eProvider->setName($provider->getName());
        $eProvider->setSlug($provider->getSlug());
        $eProvider->setPhone($provider->getPhone());

        $managers = $provider->getManagers()->getIterator();

        foreach ($managers as $manager) {
            $managerProxy = $this->em->getReference('EVT\CoreDomainBundle\Entity\GenericUser', $manager->getId());
            $eProvider->addGenericUser($managerProxy);
        }

        return $eProvider;
    }
}
