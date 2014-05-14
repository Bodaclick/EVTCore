<?php

namespace EVT\CoreDomainBundle\Repository;

use EVT\CoreDomainBundle\Events\UserEvent;
use EVT\CoreDomainBundle\Events\Event;
use EVT\CoreDomain\RepositoryInterface as DomainRepository;
use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Model\UserManager;
use BDK\AsyncEventDispatcher\AsyncEventDispatcherInterface;
use EVT\CoreDomainBundle\Factory\PaginatorFactory;

/**
 * UserRepository
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright Bodaclick S.A
 */
class UserRepository extends EntityRepository implements DomainRepository
{
    private $userManager;
    private $userMapping;
    private $asyncDispatcher;
    private $leadRepo;
    private $paginator;

    public function setAsyncDispatcher(AsyncEventDispatcherInterface $asyncDispatcher)
    {
        $this->asyncDispatcher = $asyncDispatcher;
    }

    public function setLeadRepository(LeadRepository $leadRepo)
    {
        $this->leadRepo = $leadRepo;
    }

    public function setPaginator($paginator)
    {
        $this->paginator = $paginator;
    }

    public function save($user)
    {
        if (!$user instanceof \EVT\CoreDomain\User\User) {
            throw new \InvalidArgumentException('Wrong object in UserRepository');
        }

        $eventName = Event::ON_CREATE_USER;
        if (!empty($user->getId())) {
            $eventName = Event::ON_UPDATE_USER;
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

        if ($eventName == Event::ON_CREATE_USER) {
            $lastLead = $this->leadRepo->getLastLeadByEmail($user->getEmail());
            $event = new UserEvent($user, $lastLead->getShowroom()->getVertical(), $eventName);
            $this->asyncDispatcher->dispatch($event);
        }
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

    public function setUserMapping($userMapping)
    {
        $this->userMapping = $userMapping;
    }

    public function getManagers($username, $page = 1)
    {
        if (empty($username)) {
            return null;
        }

        if (empty($page) || !is_numeric($page)) {
            throw new \InvalidArgumentException('Page not valid', 0);
        }

        $user = $this->userManager->findUserByUsername($username);

        $arrayDomManagers = [];
        if (null !== $user && in_array("ROLE_EMPLOYEE", $user->getRoles())) {
            $manager = $this->createQueryBuilder('u');
            $manager->select('u')
                ->where('u.roles LIKE :roles')
                ->setParameter('roles', '%"ROLE_MANAGER"%');
            $managerUsers = $manager->getQuery()->getResult();

            $pagination = $this->paginator->paginate($managerUsers, $page, 10);

            foreach ($pagination->getItems() as $managerUser) {
                $arrayDomManagers[] = $this->userMapping->mapEntityToDomain($managerUser);
            }
        }

        if (sizeof($arrayDomManagers) === 0) {
            return null;
        }

        return PaginatorFactory::create($pagination, $arrayDomManagers);
    }

    public function getManagerById($id)
    {
        $manager = $this->createQueryBuilder('u');
        $manager->select('u')
            ->where('u.roles LIKE :roles')
            ->andWhere('u.id  = :id')
            ->setParameter('roles', '%"ROLE_MANAGER"%')
            ->setParameter('id', $id);
        $eGenericUser = $manager->getQuery()->getOneOrNullResult();
        if (null === $eGenericUser) {
            return null;
        }
        return $this->userMapping->mapEntityToDomain($eGenericUser);
    }

    public function getManagerByUsername($username)
    {
        $manager = $this->createQueryBuilder('u');
        $manager->select('u')
            ->where('u.roles LIKE :roles')
            ->andWhere('u.username  = :username')
            ->setParameter('roles', '%"ROLE_MANAGER"%')
            ->setParameter('username', $username);
        $eGenericUser = $manager->getQuery()->getOneOrNullResult();
        if (null === $eGenericUser) {
            return null;
        }
        return $this->userMapping->mapEntityToDomain($eGenericUser);
    }

    public function getEmployees($username, $page = 1)
    {
        if (empty($username)) {
            return null;
        }

        if (empty($page) || !is_numeric($page)) {
            throw new \InvalidArgumentException('Page not valid', 0);
        }

        $user = $this->userManager->findUserByUsername($username);

        $arrayDomManagers = [];
        if (null !== $user && in_array("ROLE_EMPLOYEE", $user->getRoles())) {
            $manager = $this->createQueryBuilder('u');
            $manager->select('u')
                ->where('u.roles LIKE :roles')
                ->setParameter('roles', '%"ROLE_EMPLOYEE"%');
            $managerUsers = $manager->getQuery()->getResult();

            $pagination = $this->paginator->paginate($managerUsers, $page, 10);

            foreach ($pagination->getItems() as $managerUser) {
                $arrayDomManagers[] = $this->userMapping->mapEntityToDomain($managerUser);
            }
        }

        if (sizeof($arrayDomManagers) === 0) {
            return null;
        }

        return PaginatorFactory::create($pagination, $arrayDomManagers);
    }

    public function getEmployeeByUsername($username)
    {
        $manager = $this->createQueryBuilder('u');
        $manager->select('u')
            ->where('u.roles LIKE :roles')
            ->andWhere('u.username  = :username')
            ->setParameter('roles', '%"ROLE_EMPLOYEE"%')
            ->setParameter('username', $username);
        $eGenericUser = $manager->getQuery()->getOneOrNullResult();
        if (null === $eGenericUser) {
            return null;
        }
        return $this->userMapping->mapEntityToDomain($eGenericUser);
    }

    public function resetPassword ($username)
    {
        $user = $this->userManager->findUserByEmail($username);
        $user->setPlainPassword(substr(uniqid(microtime(true), true), -6));
        $password = $user->getPlainPassword();
        $this->userManager->updatePassword($user);
        $this->_em->persist($user);
        $this->_em->flush();

        return $password;
    }
}
