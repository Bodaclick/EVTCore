<?php

namespace EVT\CoreDomainBundle\Mapping;

use EVT\CoreDomainBundle\Entity\Showroom;
use \EVT\CoreDomain\Provider\Showroom as DShowroom;
use Doctrine\ORM\EntityManager;

/**
 * Class ShowroomMapping
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class ShowroomMapping implements MappingInterface
{

    private $em;
    private $providerMapping;
    private $verticalMapping;

    public function __construct(EntityManager $em, ProviderMapping $providerMapping, VerticalMapping $verticalMapping)
    {
        $this->em = $em;
        $this->providerMapping = $providerMapping;
        $this->verticalMapping = $verticalMapping;
    }


    public function mapEntityToDomain($showroom)
    {
        $provider = $this->providerMapping->mapEntityToDomain($showroom->getProvider());
        $vertical = $this->verticalMapping->mapEntityToDomain($showroom->getVertical());
        $dShowroom = new DShowroom($provider, $vertical, $showroom->getScore());
        $dShowroom->changeName($showroom->getName());
        $dShowroom->changeSlug($showroom->getSlug());
        $dShowroom->changePhone($showroom->getPhone());
        return $dShowroom;
    }

    public function mapDomainToEntity($showroom)
    {
        $providerEntity = $this->em->getReference(
            'EVT\CoreDomainBundle\Entity\Provider',
            $showroom->getProvider()->getId()
        );

        $verticalEntity = $this->em->getReference(
            'EVT\CoreDomainBundle\Entity\Vertical',
            $showroom->getVertical()->getDomain()
        );

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
