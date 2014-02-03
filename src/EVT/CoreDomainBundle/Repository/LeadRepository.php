<?php

namespace EVT\CoreDomainBundle\Repository;

use EVT\CoreDomainBundle\Mapping\LeadMapping;
use EVT\CoreDomain\Provider\Showroom;
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
 * @author Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright Bodaclick S.A
 */
class LeadRepository extends EntityRepository implements DomainRepository
{
    public function save($lead)
    {
        if (!$lead instanceof \EVT\CoreDomain\Lead\Lead) {
            throw new \InvalidArgumentException('Wrong object in LeadRepository');
        }

        $leadEntity = $this->mapper->mapDomainToEntity($lead);
        $leadInfo = $lead->getInformationBag();
        $this->_em->persist($leadEntity);
        foreach ($leadInfo as $key => $element) {
            $infoEntity = new ORMLeadInformation();
            $infoEntity->setKey($key);
            $infoEntity->setValue($element);
            $infoEntity->setLead($leadEntity);
            $this->_em->persist($infoEntity);
        }
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

    public function findByShowroomEmailSeconds(Showroom $showroom, $email, $seconds)
    {
        $leads = $this->_em->createQueryBuilder()
            ->select('l')
            ->from('EVTCoreDomainBundle:Lead', 'l')
            ->where('l.userEmail = :email')
            ->andWhere('l.showroom = :showroomId')
            ->andWhere('l.createdAt BETWEEN :fromDate AND :toDate')
            ->setParameter('email', $email)
            ->setParameter('showroomId', $showroom->getId())
            ->setParameter('fromDate', new \DateTime('-'.$seconds.' second', new \DateTimeZone('UTC')))
            ->setParameter('toDate', new \DateTime(null, new \DateTimeZone('UTC')))
            ->getQuery()
            ->getResult();

        $domainLeads = [];

        foreach ($leads as $lead) {
            array_push($domainLeads, $entityMapper->map($lead));
        }
        return $domainLeads;
    }

    public function setMapper(LeadMapping $mapper)
    {
        $this->mapper = $mapper;
    }
}
