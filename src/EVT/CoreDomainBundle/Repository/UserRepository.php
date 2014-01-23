<?php

namespace EVT\CoreDomainBundle\Repository;

use EVT\CoreDomain\RepositoryInterface as DomainRepository;
use EVT\CoreDomainBundle\Entity\GenericUser;
use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright Bodaclick S.A
 */
class UserRepository extends EntityRepository implements DomainRepository
{
    public function save($user)
    {
        if (!$user instanceof \EVT\CoreDomain\User\User) {
            throw new \InvalidArgumentException('Wrong object in UserRepository');
        }

        $entity = new GenericUser();
        $entity->setEmail($user->getEmail());
        $entity->setName($user->getPersonalInformation()->getName());
        $entity->setSurnames($user->getPersonalInformation()->getSurnames());
        $entity->setPhone($user->getPersonalInformation()->getPhone());
        $this->_em->persist($entity);
        $this->_em->flush();
        $this->setUserId($entity->getId(), $user);
    }

    public function delete($user)
    {
    }

    public function update($user)
    {
    }

    public function findAll()
    {
    }

    private function setUserId($id, $user)
    {
        $rflUser = new \ReflectionClass($user);
        $rflId = $rflUser->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($user, $id);
    }
}
