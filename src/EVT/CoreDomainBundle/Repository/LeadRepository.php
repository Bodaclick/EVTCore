<?php

namespace EVT\CoreDomainBundle\Repository;

use EVT\CoreDomain\Lead\LeadRepositoryInterface as DomainRepository;
use EVT\CoreDomain\Lead\LeadId;
use EVT\CoreDomainBundle\Entity\Lead;
use EVT\CoreDomainBundle\Mapping\LeadToEntityMapping;
use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright Bodaclick S.A
 */
class LeadRepository extends EntityRepository implements DomainRepository
{
    public function save($lead)
    {
        if (!$lead instanceof \EVT\CoreDomain\Lead\Lead) {
            throw new \InvalidArgumentException('Wrong object in LeadRepository');
        }

        $mapper = new LeadToEntityMapping();

        $entity = $mapper->map($lead);
        $this->_em->persist($entity);
        $this->_em->flush();
        $this->setLeadId($entity->getId(), $lead);
    }

    public function delete($lead)
    {
    }

    public function update($lead)
    {
    }

    public function findAll()
    {
    }

    public function findByCountry()
    {
    }

    public function findByEventType()
    {
    }

    private function setLeadId($id, $lead)
    {
        $lid = new LeadId($id);
        $rflLead = new \ReflectionClass($lead);
        $rflId = $rflLead->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($lead, $lid->getValue());
    }
}
