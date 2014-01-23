<?php

namespace EVT\CoreDomainBundle\Test\Mapping;

use EVT\CoreDomainBundle\Entity\Lead;
use EVT\CoreDomainBundle\Mapping\EntityToLeadMapping;

class EntityToLeadMappingTest extends \PHPUnit_Framework_TestCase
{
    public function testLeadIsMapped()
    {
        $showroom = $this->getMockBuilder('EVT\CoreDomainBundle\Entity\Showroom')->disableOriginalConstructor()
            ->getMock();

        $entity = new Lead();
        $entity->setUserName('name');
        $entity->setUserSurnames('surname');
        $entity->setUserPhone('phone');
        $entity->setUserEmail('valid@email.com');
        $entity->setEventType(1);
        $entity->setEventDate(new \DateTime('2014-10-15', new \DateTimeZone('UTC')));
        $entity->setEventLocationLat(15);
        $entity->setEventLocationLong(10);
        $entity->setEventLocationAdminLevel1('Mostoles');
        $entity->setEventLocationAdminLevel2('Madrid');
        $entity->setEventLocationCountry('spain');
        $entity->setShowroom($showroom);

        $mapping = new EntityToLeadMapping();
        $lead = $mapping->map($entity);

        $personalInfo = $lead->getPersonalInformation();
        $event = $lead->getEvent();

        $this->assertInstanceOf('EVT\CoreDomainBundle\Entity\Lead', $entity);
        $this->assertEquals($personalInfo->getName(), $entity->getUserName());
        $this->assertEquals($personalInfo->getSurnames(), $entity->getUserSurnames());
        $this->assertEquals($personalInfo->getPhone(), $entity->getUserPhone());
        $this->assertEquals($lead->getEmail(), $entity->getUserEmail());
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
