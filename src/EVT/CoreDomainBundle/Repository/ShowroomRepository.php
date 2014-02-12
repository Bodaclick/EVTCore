<?php

namespace EVT\CoreDomainBundle\Repository;

use EVT\CoreDomainBundle\Events\ShowroomEvent;
use EVT\CoreDomainBundle\Events\Event;
use EVT\CoreDomain\Provider\ShowroomRepositoryInterface as DomainRepository;
use Doctrine\ORM\EntityRepository;
use EVT\CoreDomainBundle\Mapping\ShowroomMapping;
use BDK\AsyncEventDispatcher\AsyncEventDispatcherInterface;

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

    public function setAsyncDispatcher(AsyncEventDispatcherInterface $asyncDispatcher)
    {
        $this->asyncDispatcher = $asyncDispatcher;
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
}
