<?php

namespace EVT\CoreDomainBundle\Mapping;

use EVT\CoreDomainBundle\Entity\Showroom;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;


class ShowroomMapping implements MappingInterface
{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    public function mapEntityToDomain($showroom)
    {
        throw new Exception("To implement");
    }


    public function mapDomainToEntity($showroom)
    {
        $providerEntity = $this->em->getReference('EVT\CoreDomainBundle\Entity\Provider', $showroom->getProvider()->getId());

        $verticalEntity = $this->em->getReference('EVT\CoreDomainBundle\Entity\Vertical', $showroom->getVertical()->getDomain());

        $showroomEntity = new Showroom();
        if (null !== $showroom->getId()) {
            $rflProvider = new \ReflectionClass($showroomEntity);
            $rflId = $rflProvider->getProperty('id');
            $rflId->setAccessible(true);
            $rflId->setValue($showroomEntity, $showroom->getId());
        }

        $showroomEntity->setProvider($providerEntity);
        $showroomEntity->setVertical($verticalEntity);
        $showroomEntity->setScore($showroom->getScore());
        $showroomEntity->setName($showroom->getName());
        $showroomEntity->setPhone($showroom->getPhone());
        $showroomEntity->setSlug($showroom->getSlug());

        return $showroomEntity;
    }
} 