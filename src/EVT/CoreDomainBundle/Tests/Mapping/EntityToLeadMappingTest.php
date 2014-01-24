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
        $this->assertEquals($entity->getUserName(), $personalInfo->getName());
        $this->assertEquals($entity->getUserSurnames(), $personalInfo->getSurnames());
        $this->assertEquals($entity->getUserPhone(), $personalInfo->getPhone());
        $this->assertEquals($entity->getUserEmail(), $lead->getEmail());
        $this->assertEquals($entity->getEventType(), $event->getEventType()->getType());
        $this->assertEquals($entity->getEventDate(), $event->getDate());
        $this->assertEquals($entity->getEventLocationLat(), $event->getLocation()->getLatLong()['lat']);
        $this->assertEquals($entity->getEventLocationLong(), $event->getLocation()->getLatLong()['long']);
        $this->assertEquals($entity->getEventLocationAdminLevel1(), $event->getLocation()->getAdminLevel1());
        $this->assertEquals($entity->getEventLocationAdminLevel2(), $event->getLocation()->getAdminLevel2());
        $this->assertEquals($entity->getEventLocationCountry(), $event->getLocation()->getCountry());
        $this->assertInstanceOf('EVT\CoreDomainBundle\Entity\Showroom', $entity->getShowroom());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $entity->getLeadInformation());
    }
}
