<?php

namespace EVT\CoreDomainBundle\Repository;

use EVT\CoreDomain\RepositoryInterface as DomainRepository;
use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Model\UserManager;

/**
 * UserRepository
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright Bodaclick S.A
 */
class UserRepository extends EntityRepository implements DomainRepository
{
    protected $userManager;

    public function save($user)
    {
        if (!$user instanceof \EVT\CoreDomain\User\User) {
            throw new \InvalidArgumentException('Wrong object in UserRepository');
        }
        $entity = $this->userManager->createUser();
        $entity->setEmail($user->getEmail());
        $entity->setUsername($user->getEmail());
        $entity->setName($user->getPersonalInformation()->getName());
        $entity->setSurnames($user->getPersonalInformation()->getSurnames());
        $entity->setPhone($user->getPersonalInformation()->getPhone());
        $entity->setPlainPassword(uniqid(microtime(true), true));
        $this->userManager->updateUser($entity);
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

    public function setUserManager(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }
    
    public function getRetriveManagersQueryBuilder()
    {
        return $this->createQueryBuilder('u');
    }
}
