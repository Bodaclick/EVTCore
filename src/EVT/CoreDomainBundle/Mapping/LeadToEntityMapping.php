<?php

namespace EVT\CoreDomainBundle\Mapping;

use EVT\CoreDomain\Lead\Lead;
use EVT\CoreDomainBundle\Entity\Lead as ORMLead;
use EVT\CoreDomainBundle\Entity\LeadInformation as ORMLeadInformation;

/**
 * LeadToEntityMapping
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright Bodaclick S.A
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
        $entity->setUserName($lead->getPersonalInformation()->name);
        $entity->setUserSurnames($lead->getPersonalInformation()->surnames);
        $entity->setUserPhone($lead->getPersonalInformation()->phone);
        $entity->setUserEmail($lead->getEmail()->getEmail());
        $entity->setEventType($lead->getEvent()->getEventType()->getType());
        $entity->setEventDate($lead->getEvent()->getDate());
        $entity->setEventLocationLat($lead->getEvent()->getLocation()->getLatLong()['lat']);
        $entity->setEventLocationLong($lead->getEvent()->getLocation()->getLatLong()['long']);
        $entity->setEventLocationAdminLevel1($lead->getEvent()->getLocation()->getAdminLevel1());
        $entity->setEventLocationAdminLevel2($lead->getEvent()->getLocation()->getAdminLevel2());
        $entity->setEventLocationCountry($lead->getEvent()->getLocation()->getCountry());
        $entity->setShowroom($lead->getShowroom());
        $leadInfo = $lead->getInformationBag();
        foreach ($leadInfo as $key => $element) {
            $infoEntity = new ORMLeadInformation();
            $infoEntity->setKey($key);
            $infoEntity->setValue($element);
            $entity->addLeadInformation($infoEntity);
        }
        return $entity;
    }
}
