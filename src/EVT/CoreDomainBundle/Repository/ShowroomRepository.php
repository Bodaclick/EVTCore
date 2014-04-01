<?php

namespace EVT\CoreDomainBundle\Repository;

use EVT\CoreDomainBundle\Events\ShowroomEvent;
use EVT\CoreDomainBundle\Events\Event;
use EVT\CoreDomain\Provider\ShowroomRepositoryInterface as DomainRepository;
use Doctrine\ORM\EntityRepository;
use EVT\CoreDomainBundle\Mapping\ShowroomMapping;
use BDK\AsyncEventDispatcher\AsyncEventDispatcherInterface;
use EVT\CoreDomainBundle\Factory\PaginatorFactory;

/**
 * Class ShowroomRepository
 *
 * @author    Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class ShowroomRepository extends EntityRepository implements DomainRepository
{

    private $mapping;
    private $asyncDispatcher;
    private $paginator;
    private $userRepo;

    public function setAsyncDispatcher(AsyncEventDispatcherInterface $asyncDispatcher)
    {
        $this->asyncDispatcher = $asyncDispatcher;
    }

    public function setPaginator($paginator)
    {
        $this->paginator = $paginator;
    }

    public function setUserRepo($userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function save($showroom)
    {
        $entity = $this->mapping->mapDomainToEntity($showroom);
        $this->_em->persist($entity);
        $this->_em->flush();

        $eventName = Event::ON_CREATE_SHOWROOM;
        if (!empty($showroom->getId())) {
            $eventName = Event::ON_UPDATE_SHOWROOM;
        }

        $this->setId($entity->getId(), $showroom);

        $event = new ShowroomEvent($showroom, $eventName);
        $this->asyncDispatcher->dispatch($event);
    }

    public function delete($showroom)
    {
    }

    public function findOneById($id)
    {
        if (!$showroom = parent::findOneById($id)) {
            return null;
        }
        return $this->mapping->mapEntityToDomain($showroom);
    }

    public function findAll()
    {
    }

    public function setMapper(ShowroomMapping $mapping)
    {
        $this->mapping = $mapping;
    }

    private function setId($id, $showroom)
    {
        $rflShowroom = new \ReflectionClass($showroom);
        $rflId = $rflShowroom->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($showroom, $id);
    }

    public function findByOwner($username, $page = 1)
    {
        if (empty($username)) {
            return null;
        }

        $showrooms = $this->_em->createQuery(
            "SELECT s
            FROM EVTCoreDomainBundle:Showroom s
                JOIN s.provider p
                JOIN p.genericUser u
            WHERE u.username = :username
            ORDER BY s.id ASC"
        )
            ->setParameter("username", $username);

        $pagination = $this->paginator->paginate($showrooms, $page, 10);

        if ($pagination->count() == 0 && null !== $this->userRepo->getEmployeeByUsername($username)) {
            $showrooms = $this->_em->createQuery(
                "SELECT s
                FROM EVTCoreDomainBundle:Showroom s
                ORDER BY s.id DESC"
            );

            $pagination = $this->paginator->paginate($showrooms, $page, 10);
        }

        $arrayDomShowrooms = [];
        foreach ($pagination->getItems() as $showroom) {
            $arrayDomShowrooms[] = $this->mapping->mapEntityToDomain($showroom);
        }
        if (sizeof($arrayDomShowrooms) === 0) {
            return null;
        }

        return PaginatorFactory::create($pagination, $arrayDomShowrooms);
    }
}
