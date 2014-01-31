<?php

namespace EVT\CoreDomainBundle\Mapping;

use EVT\CoreDomainBundle\Entity\Showroom;
use EVT\CoreDomain\Provider\Showroom as DomainShowroom;


class ShowroomMapping {

    public function mapEntityToDomain(Showroom $showroom)
    {

    }

    /**
     * map
     *
     * @param EVT\CoreDomain\Provider\Showroom
     * @return EVT\CoreDomainBundle\Entity\Showroom $showroom
     */
    public function mapDomainToEntity(DomainShowroom $showroom)
    {
        $mapProvider = new ProviderToEntity();
        $providerEntity = $mapProvider->map($showroom->getProvider());

        $mapVertical = new VerticalToEntity();
        $verticalEntity = $mapVertical->map($showroom->getVertical());

        $entity = new Showroom();
        if (null !== $showroom->getId()) {
            $rflProvider = new \ReflectionClass($entity);
            $rflId = $rflProvider->getProperty('id');
            $rflId->setAccessible(true);
            $rflId->setValue($entity, $showroom->getId());
        }
        $entity->setName($showroom->getName());
        $entity->setPhone($showroom->getPhone());
        $entity->setProvider($providerEntity);
        $entity->setScore($showroom->getScore());
        $entity->setSlug($showroom->getSlug());
        $entity->setVertical($verticalEntity);

        return $entity;
    }
} 