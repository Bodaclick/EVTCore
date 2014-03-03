<?php

namespace EVT\CoreDomainBundle\Test\Mapping;

use EVT\CoreDomainBundle\Entity\Lead;
use EVT\CoreDomainBundle\Entity\Showroom;
use EVT\CoreDomainBundle\Mapping\LeadMapping;
use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\Lead\EventType;
use EVT\CoreDomain\Lead\LeadInformationBag;
use EVT\CoreDomain\Lead\Location;
use EVT\CoreDomain\User\User;
use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\Email;

class LeadMappingTest extends \PHPUnit_Framework_TestCase
{
    public function testEntityToDomain()
    {
        $dShowroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()
            ->getMock();
        $showroomMapper = $this->getMockBuilder('EVT\CoreDomainBundle\Mapping\ShowroomMapping')
            ->disableOriginalConstructor()->getMock();
        $showroomMapper->expects($this->once())->method('mapEntityToDomain')->will($this->returnValue($dShowroom));

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();

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
        $entity->setShowroom(new Showroom());
        $entity->setCreatedAt(new \DateTime('2013-10-15', new \DateTimeZone('UTC')));
        $entity->setReadAt(new \DateTime('2013-10-12', new \DateTimeZone('UTC')));

        $mapping = new LeadMapping($em, $showroomMapper);
        $lead = $mapping->mapEntityToDomain($entity);

        $personalInfo = $lead->getPersonalInformation();
        $event = $lead->getEvent();

        $this->assertInstanceOf('EVT\CoreDomain\Lead\Lead', $lead);
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
        $this->assertEquals($entity->getCreatedAt(), $lead->getCreatedAt());
        $this->assertEquals($entity->getReadAt(), $lead->getReadAt());
    }

    public function testDomainToEntity()
    {
        $dShowroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()
            ->getMock();
        $dShowroom->expects($this->once())->method('getId')->will($this->returnValue(1));
        $showroomMapper = $this->getMockBuilder('EVT\CoreDomainBundle\Mapping\ShowroomMapping')
            ->disableOriginalConstructor()->getMock();
        $showroomMapper->expects($this->never())->method('mapDomainToEntity')->will($this->returnValue(new Showroom()));

        $eShowroom = $this->getMock('EVT\CoreDomainBundle\Entity\Showroom');

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $em->expects($this->at(0))->method('getReference')->will($this->returnValue($eShowroom));

        $event = new Event(
            new EventType(EventType::BIRTHDAY),
            new Location(10, 10, 'admin1', 'admin2', 'ES'),
            new \DateTime('now')
        );
        $personalInfo = new PersonalInformation('name', 'surname', 'phone');
        $user = new User(new Email('valid@email.com'), $personalInfo);
        $infoBag = new LeadInformationBag(['observations' => 'test']);
        $lead = $user->doLead($dShowroom, $event, $infoBag);
        $lead->read();

        $rflLead = new \ReflectionClass($lead);
        $rflId = $rflLead->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($lead, 10);


        $mapping = new LeadMapping($em, $showroomMapper);

        $entity = $mapping->mapDomainToEntity($lead);
        $this->assertInstanceOf('EVT\CoreDomainBundle\Entity\Lead', $entity);
        $this->assertEquals($personalInfo->getName(), $entity->getUserName());
        $this->assertEquals($personalInfo->getSurnames(), $entity->getUserSurnames());
        $this->assertEquals($personalInfo->getPhone(), $entity->getUserPhone());
        $this->assertEquals($user->getEmail()->getEmail(), $entity->getUserEmail());
        $this->assertEquals($event->getEventType()->getType(), $entity->getEventType());
        $this->assertEquals($event->getDate(), $entity->getEventDate());
        $this->assertEquals($event->getLocation()->getLatLong()['lat'], $entity->getEventLocationLat());
        $this->assertEquals($event->getLocation()->getLatLong()['long'], $entity->getEventLocationLong());
        $this->assertEquals($event->getLocation()->getAdminLevel1(), $entity->getEventLocationAdminLevel1());
        $this->assertEquals($event->getLocation()->getAdminLevel2(), $entity->getEventLocationAdminLevel2());
        $this->assertEquals($event->getLocation()->getCountry(), $entity->getEventLocationCountry());
        $this->assertEquals($lead->getCreatedAt(), $entity->getCreatedAt());
        $this->assertNotNull($entity->getReadAt());
        $this->assertEquals(10, $entity->getId());
    }
}
