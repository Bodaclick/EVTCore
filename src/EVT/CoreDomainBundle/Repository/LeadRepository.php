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
use EVT\CoreDomainBundle\Model\LeadInformation as ORMLeadInformation;
use EVT\CoreDomainBundle\Mapping\LeadToEntityMapping;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use EVT\CoreDomainBundle\Factory\PaginatorFactory;

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

    public function save($lead)
    {
        if (!$lead instanceof \EVT\CoreDomain\Lead\Lead) {
            throw new \InvalidArgumentException('Wrong object in LeadRepository');
        }

        $leadEntity = $this->mapper->mapDomainToEntity($lead);

        $leadInfo = $lead->getInformationBag();

        $uowEntity = $leadEntity;
        $notificationEvent = Event::ON_CREATE_LEAD;
        if (!empty($lead->getId())) {
            $metadata = $this->_em->getClassMetaData(get_class($leadEntity));
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            $uowEntity = $this->_em->merge($leadEntity);
            $notificationEvent = Event::ON_UPDATE_LEAD;
        }
        $this->_em->persist($uowEntity);

        foreach ($leadInfo as $key => $element) {
            $infoEntity = new ORMLeadInformation();
            $infoEntity->setKey($key);
            $infoEntity->setValue($element);
            $leadEntity->addLeadInformation($infoEntity);
        }

        $this->_em->flush();
        $this->setLeadId($leadEntity->getId(), $lead);

        $event = new LeadEvent($lead, $notificationEvent);
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
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (is_null($lead)) {
            return null;
        }

        return $this->mapper->mapEntityToDomain($lead);
    }

    public function findByOwner($username, $page = 1)
    {
        if (empty($username)) {
            return null;
        }

        if (empty($page) || !is_int($page)) {
            throw new \InvalidArgumentException('Page not valid', 0);
        }

        $leads = $this->_em->createQuery(
            "SELECT l
            FROM EVTCoreDomainBundle:Lead l
                JOIN l.showroom s
                JOIN s.provider p
                JOIN p.genericUser u
            WHERE u.username = :username
            ORDER BY l.id DESC"
        )
            ->setParameter("username", $username);

        $pagination = $this->paginator->paginate($leads, $page, 10);

        if ($pagination->count() == 0 && null !== $this->userRepo->getEmployeeByUsername($username)) {
            $leads = $this->_em->createQuery(
                "SELECT l
                FROM EVTCoreDomainBundle:Lead l
                ORDER BY l.id DESC"
            );

            $pagination = $this->paginator->paginate($leads, $page, 10);
        }

        $arrayDomLeads = [];
        foreach ($pagination->getItems() as $lead) {
            $arrayDomLeads[] = $this->mapper->mapEntityToDomain($lead);
        }
        if (sizeof($arrayDomLeads) === 0) {
            return null;
        }

        return PaginatorFactory::create($pagination, $arrayDomLeads);
    }

    public function findByIdOwner($id, $username)
    {
        if (empty($id)) {
            return null;
        }

        try {
            $lead = $this->_em->createQuery(
                "SELECT l
                FROM EVTCoreDomainBundle:Lead l
                    JOIN l.showroom s
                    JOIN s.provider p
                    JOIN p.genericUser u
                WHERE u.username = :username
                AND l.id = :id"
            )
            ->setParameter("username", $username)
            ->setParameter("id", $id)
            ->getSingleResult();
        } catch (\Exception $e) {
            return null;
        }

        return $leadDom = $this->mapper->mapEntityToDomain($lead);
    }

    public function count()
    {
        return $this->_em->createQuery("select count(l.id) from EVTCoreDomainBundle:Lead l")
           ->getSingleScalarResult();
    }
}
