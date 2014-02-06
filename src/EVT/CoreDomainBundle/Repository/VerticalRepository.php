<?php

namespace EVT\CoreDomainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use EVT\CoreDomain\Provider\Provider;
use EVT\CoreDomain\Provider\Showroom;
use EVT\CoreDomain\Provider\Vertical;
use EVT\CoreDomain\Provider\VerticalRepositoryInterface as DomainRepository;
use EVT\CoreDomainBundle\Mapping\ShowroomMapping;
use EVT\CoreDomainBundle\Mapping\VerticalMapping;

class VerticalRepository extends EntityRepository implements DomainRepository
{
    private $mapping;
    private $showroomMapper;

    public function save($vertical)
    {
    }

    public function delete($vertical)
    {
    }

    public function update($vertical)
    {
    }

    public function findAll()
    {
    }

    public function findOneByDomain($domain)
    {
        if (!$vertical = parent::findOneByDomain($domain)) {
            return null;
        }
        return $this->mapping->mapEntityToDomain($vertical);
    }

    /**
     * @param Vertical $vertical
     * @param Provider $provider
     * @return Showroom
     */
    public function findShowroom(Vertical $vertical, Provider $provider)
    {
        $qb = $this->_em->createQuery(
            'SELECT s FROM EVTCoreDomainBundle:Showroom s WHERE s.provider = :provider AND s.vertical = :vertical'
        );
        $qb->setParameter('provider', $provider->getId());
        $qb->setParameter('vertical', $vertical->getDomain());
        if ($showroom = $qb->getOneOrNullResult()) {
            return $this->showroomMapper->mapEntityToDomain($showroom);
        }
    }

    public function setMapper(VerticalMapping $mapping)
    {
        $this->mapping = $mapping;
    }

    public function setShowroomMapper(ShowroomMapping $mapping)
    {
        $this->showroomMapper = $mapping;
    }
}
