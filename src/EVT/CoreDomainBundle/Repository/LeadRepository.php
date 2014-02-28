<?php

namespace EVT\CoreDomainBundle\Repository;

use BDK\AsyncEventDispatcher\AsyncEventDispatcherInterface;
use EVT\CoreDomainBundle\Events\LeadEvent;
use EVT\CoreDomainBundle\Events\Event;
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
 * @author    Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class LeadRepository extends EntityRepository implements DomainRepository
{
    private $asyncDispatcher;

    public function setAsyncDispatcher(AsyncEventDispatcherInterface $asyncDispatcher)
    {
        $this->asyncDispatcher = $asyncDispatcher;
    }

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

        $event = new LeadEvent($lead, Event::ON_CREATE_LEAD);
        $this->asyncDispatcher->dispatch($event);
    }

    public function delete($lead)
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
            array_push($domainLeads, $this->mapper->mapEntityToDomain($lead));
        }
        return $domainLeads;
    }

    public function setMapper(LeadMapping $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     *
     * @param string $email The email to find with
     * @return \EVT\CoreDomain\Lead\Lead the last lead
     */
    public function getLastLeadByEmail($email)
    {
        $lead = $this->_em->createQueryBuilder()
            ->select('l')
            ->from('EVTCoreDomainBundle:Lead', 'l')
            ->where('l.userEmail = :email')
            ->setParameter('email', $email)
            ->orderBy('l.createdAt', 'desc')
            ->getQuery()
            ->getOneOrNullResult();

        if (is_null($lead)) {
            return null;
        }

        return $this->mapper->mapEntityToDomain($lead);
    }

    public function findByOwner($username)
    {
        if (empty($username)) {
            return null;
        }

        $leads = $this->_em->createQuery(
            "select l
            from EVTCoreDomainBundle:Lead l
            join l.showroom s
            join s.provider p
            join p.genericUser u
            where u.username = :username"
        )
            ->setParameter("username", $username)
            ->getResult();

        if (empty($leads)) {
            return null;
        } else {
            foreach ($leads as $lead) {
                $arrayDomLeads[] = $this->mapper->mapEntityToDomain($lead);
            }
        }

        return $arrayDomLeads;
    }
}
