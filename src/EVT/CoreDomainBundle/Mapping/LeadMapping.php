<?php

namespace EVT\CoreDomainBundle\Mapping;

use EVT\CoreDomain\Lead\EventType;
use EVT\CoreDomain\Lead\Location;
use EVT\CoreDomain\InformationBag;
use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\Email;
use EVT\CoreDomain\Lead\LeadId;
use EVT\CoreDomain\Lead\Lead as DomainLead;
use EVT\CoreDomainBundle\Entity\Lead;

/**
 * LeadMapping
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @author    Eduardo Gulias Davis >eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class LeadMapping
{
    private $showroomMapper;

    public function __construct(ShowroomMapping $showroomMapper)
    {
        $this->showroomMapper = $showroomMapper;
    }

    /**
     * @param Lead $lead
     * @return DomainLead
     */
    public function mapEntityToDomain(Lead $lead)
    {
        $domain = new DomainLead(
            new LeadId($lead->getId()),
            new PersonalInformation($lead->getUserName(), $lead->getUserSurnames(), $lead->getUserPhone()),
            new Email($lead->getUserEmail()),
            $this->showroomMapper->mapEntityToDomain($lead->getShowroom()),
            new Event(
                new EventType($lead->getEventType()),
                new Location(
                    $lead->getEventLocationLat(),
                    $lead->getEventLocationLong(),
                    $lead->getEventLocationAdminLevel1(),
                    $lead->getEventLocationAdminLevel2(),
                    $lead->getEventLocationCountry()
                ),
                $lead->getEventDate()
            )
        );

        return $domain;
    }

    /**
     * @param DomainLead $lead
     * @return Lead
     */
    public function mapDomainToEntity(DomainLead $lead)
    {
        $entity = new Lead();
        $entity->setUserName($lead->getPersonalInformation()->getName());
        $entity->setUserSurnames($lead->getPersonalInformation()->getSurnames());
        $entity->setUserPhone($lead->getPersonalInformation()->getPhone());
        $entity->setUserEmail($lead->getEmail()->getEmail());
        $entity->setEventType($lead->getEvent()->getEventType()->getType());
        $entity->setEventDate($lead->getEvent()->getDate());
        $entity->setEventLocationLat($lead->getEvent()->getLocation()->getLatLong()['lat']);
        $entity->setEventLocationLong($lead->getEvent()->getLocation()->getLatLong()['long']);
        $entity->setEventLocationAdminLevel1($lead->getEvent()->getLocation()->getAdminLevel1());
        $entity->setEventLocationAdminLevel2($lead->getEvent()->getLocation()->getAdminLevel2());
        $entity->setEventLocationCountry($lead->getEvent()->getLocation()->getCountry());
        $entity->setShowroom($this->showroomMapper->mapDomainToEntity($lead->getShowroom()));
        return $entity;
    }
}
