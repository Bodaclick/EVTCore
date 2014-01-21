<?php

namespace EVT\CoreDomainBundle\Test\Mapping;

use EVT\CoreDomain\User\User;
use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\Email;
use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\Lead\EventType;
use EVT\CoreDomain\Lead\Location;
use EVT\CoreDomain\InformationBag;
use EVT\CoreDomainBundle\Mapping\LeadToEntityMapping;
use EVT\CoreDomainBundle\Entity\Showroom as ORMShowroom;
use EVT\CoreDomainBundle\Entity\LeadInformation as ORMLeadInformation;

class LeadToEntityMappingTest extends \PHPUnit_Framework_TestCase
{
    public function testEntityIsMapped()
    {
        $showroom = $this->getMockBuilder('EVT\CoreDomainBundle\Entity\Showroom')->disableOriginalConstructor()
            ->getMock();
        $event = new Event(new EventType('test'), new Location(10, 10, 'admin1', 'admin2', 'ES'), new \DateTime('now'));
        $personalInfo = new PersonalInformation('name');
        $user = new User(new Email('valid@email.com'), $personalInfo);
        $infoBag = new InformationBag(['observations' => 'test']);
        $lead = $user->doLead($showroom, $event, $infoBag);

        $mapping = new LeadToEntityMapping();

        $entity = $mapping->map($lead);
        $this->assertInstanceOf('EVT\CoreDomainBundle\Entity\Lead', $entity);
        $this->assertEquals($personalInfo->name, $entity->getUserName());
        $this->assertEquals($personalInfo->surnames, $entity->getUserSurnames());
        $this->assertEquals($personalInfo->phone, $entity->getUserPhone());
        $this->assertEquals($user->getEmail()->getEmail(), $entity->getUserEmail());
        $this->assertEquals($event->getEventType()->getType(), $entity->getEventType());
        $this->assertEquals($event->getDate(), $entity->getEventDate());
        $this->assertEquals($event->getLocation()->getLatLong()['lat'], $entity->getEventLocationLat());
        $this->assertEquals($event->getLocation()->getLatLong()['long'], $entity->getEventLocationLong());
        $this->assertEquals($event->getLocation()->getAdminLevel1(), $entity->getEventLocationAdminLevel1());
        $this->assertEquals($event->getLocation()->getAdminLevel2(), $entity->getEventLocationAdminLevel2());
        $this->assertEquals($event->getLocation()->getCountry(), $entity->getEventLocationCountry());
        $this->assertInstanceOf('EVT\CoreDomainBundle\Entity\Showroom', $entity->getShowroom());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $entity->getLeadInformation());
    }
}
