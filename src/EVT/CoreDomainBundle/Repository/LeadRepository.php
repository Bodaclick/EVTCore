<?php

namespace EVT\CoreDomainBundle\Repository;

use EVT\CoreDomain\Lead\LeadRepositoryInterface as DomainRepository;
use EVT\CoreDomain\Lead\LeadId;
use EVT\CoreDomainBundle\Entity\Lead;
use EVT\CoreDomainBundle\Entity\LeadInformation as ORMLeadInformation;
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
        $leadEntity = $mapper->map($lead);
        $leadInfo = $lead->getInformationBag();
        foreach ($leadInfo as $key => $element) {
            $infoEntity = new ORMLeadInformation();
            $infoEntity->setKey($key);
            $infoEntity->setValue($element);
            $infoEntity->setLead($leadEntity);
        }
        $this->_em->persist($leadEntity);
        $this->_em->persist($infoEntity);
        $this->_em->flush();
        $this->setLeadId($leadEntity->getId(), $lead);
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
