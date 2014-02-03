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
class UserMapping
{
    public function mapEntityToDomain(GenericUser $user)
    {
        $personalInfo = new PersonalInformation($user->getName(), $user->getSurnames(), $user->getPhone());
        $manager = new Manager($user->getEmail(), $personalInfo);

        if (null !== $user->getId()) {
            $rflMamager = new \ReflectionClass($manager);
            $rflId = $rflMamager->getProperty('id');
            $rflId->setAccessible(true);
            $rflId->setValue($manager, $user->getId());
        }

        return $manager;
    }
}
