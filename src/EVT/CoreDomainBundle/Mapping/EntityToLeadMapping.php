<?php

namespace EVT\CoreDomainBundle\Mapping;

use EVT\CoreDomain\Lead\Location;
use EVT\CoreDomain\InformationBag;
use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\Email;
use EVT\CoreDomain\Lead\LeadId;
use EVT\CoreDomain\Lead\Lead as DomainLead;
use EVT\CoreDomainBundle\Entity\Lead;

/**
 * EntityToLeadMapping
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class EntityToLeadMapping
{
    /**
     * map
     *
     * @param EVT\CoreDomainBundle\Entity\Lead $lead
     * @return EVT\CoreDomain\Lead\Lead
     */
    public function map(Lead $lead)
    {
        $domain = new DomainLead(
            new LeadId($lead->getId()),
            new PersonalInformation($lead->getUserName(), $lead->getUserSurnames(), $lead->getUserPhone()),
            new Email($lead->getUserEmail()),
            $lead->getShowroom(),
            new Event(
                $lead->getEventType(),
                new Location(
                    $lead->getEventLocationLat(),
                    $lead->getEventLocationLong(),
                    $lead->getEventLocationAdminLevel1(),
                    $lead->getEventLocationAdminLevel2(),
                    $lead->getEventLocationCountry()
                ),
                new \DateTime($lead->getEventDate(), new \DateTimeZone('UTC'))
            )
        );

        return $domain;
    }
}
