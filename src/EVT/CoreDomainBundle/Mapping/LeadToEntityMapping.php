<?php

namespace EVT\CoreDomainBundle\Mapping;

use EVT\CoreDomain\Lead\Lead;
use EVT\CoreDomainBundle\Entity\Lead as ORMLead;

/**
 * LeadToEntityMapping
 *
 * @author    Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class LeadToEntityMapping
{
    /**
     * map
     *
     * @param Lead $lead
     * @return EVT\CoreDomainBundle\Entity\Lead
     */
    public function map(Lead $lead)
    {
        $entity = new ORMLead();
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
        $entity->setShowroom($lead->getShowroom());
        return $entity;
    }
}
