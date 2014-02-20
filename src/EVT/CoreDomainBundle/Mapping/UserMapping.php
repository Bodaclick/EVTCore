<?php

namespace EVT\CoreDomainBundle\Mapping;

use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\User\Manager;
use EVT\CoreDomainBundle\Entity\GenericUser;
use Doctrine\ORM\EntityManager;

/**
 * UserMapping
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class UserMapping implements MappingInterface
{
    public function mapEntityToDomain($object)
    {
        $personalInfo = new PersonalInformation($object->getName(), $object->getSurnames(), $object->getPhone());
        $manager = new Manager($object->getEmail(), $personalInfo, $object->getSalt(), $object->getPassword(), $object->getRoles(), $object->getUsername());

        if (null !== $object->getId()) {
            $rflMamager = new \ReflectionClass($manager);
            $rflId = $rflMamager->getProperty('id');
            $rflId->setAccessible(true);
            $rflId->setValue($manager, $object->getId());
        }

        return $manager;
    }

    public function mapDomainToEntity($object)
    {
    }
}
