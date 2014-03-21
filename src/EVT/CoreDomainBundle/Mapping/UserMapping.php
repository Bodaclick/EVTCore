<?php

namespace EVT\CoreDomainBundle\Mapping;

use EVT\CoreDomain\User\Employee;
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
        if (in_array('ROLE_MANAGER', $object->getRoles())) {
            $personalInfo = new PersonalInformation($object->getName(), $object->getSurnames(), $object->getPhone());

            $user = new Manager(
                $object->getEmail(),
                $personalInfo,
                $object->getSalt(),
                $object->getPassword(),
                $object->getRoles(),
                $object->getUsername()
            );
        } elseif (in_array('ROLE_EMPLOYEE', $object->getRoles())) {
            $personalInfo = new PersonalInformation($object->getName(), $object->getSurnames(), '-');
            $user = new Employee(
                $object->getEmail(),
                $personalInfo,
                $object->getSalt(),
                $object->getPassword(),
                $object->getRoles(),
                $object->getUsername()
            );
        } else {
            return null;
        }

        if (null !== $object->getId()) {
            $rflUser = new \ReflectionClass($user);
            $rflId = $rflUser->getProperty('id');
            $rflId->setAccessible(true);
            $rflId->setValue($user, $object->getId());
        }

        return $user;
    }

    public function mapDomainToEntity($object)
    {
    }
}
