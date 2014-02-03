<?php

namespace EVT\CoreDomainBundle\Repository;

use EVT\CoreDomain\Provider\ShowroomRepositoryInterface as DomainRepository;
use Doctrine\ORM\EntityRepository;
use EVT\CoreDomainBundle\Mapping\ShowroomMapping;

/**
 * Class ShowroomRepository
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class ShowroomRepository extends EntityRepository implements DomainRepository
{

    private $mapping;

    public function save($showroom)
    {
        $entity = $this->mapping->mapDomainToEntity($showroom);
        $this->_em->persist($entity);
        $this->_em->flush();
        $this->setId($entity->getId(), $showroom);
    }

    public function delete($showroom)
    {
    }

    public function update($showroom)
    {
    }

    public function findShowroom($id)
    {
        return $this->mapping->maptEntityToDomain(parent::find($id));
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
