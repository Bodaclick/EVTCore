<?php

namespace EVT\CoreDomainBundle\Test\Repository;

use EVT\CoreDomainBundle\Repository\LeadRepository;
use EVT\CoreDomain\User\User;
use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\Email;
use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\Lead\EventType;
use EVT\CoreDomain\Lead\Location;
use EVT\CoreDomain\Lead\LeadInformationBag;

class LeadRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testSaveLead()
    {
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $metadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()->getMock();
        $em->expects($this->once())->method('flush')->will($this->returnValue(null));
        $em->expects($this->any())->method('persist')->will($this->returnSelf());

        $eLead = $this->getMock('EVT\CoreDomainBundle\Entity\Lead');
        $eLead->expects($this->once())->method('getId')->will($this->returnValue(1));

        $leadMapper = $this->getMockBuilder('EVT\CoreDomainBundle\Mapping\LeadMapping')
            ->disableOriginalConstructor()->getMock();
        $leadMapper->expects($this->once())->method('mapDomainToEntity')->will($this->returnValue($eLead));

        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()
            ->getMock();

        $asyncDispatcher = $this->getMockBuilder(
            'BDK\AsyncEventDispatcher\AsyncEventDispatcher'
        )->disableOriginalConstructor()->getMock();

        $asyncDispatcher->expects($this->once())
            ->method('dispatch')->will($this->returnValue($this->returnSelf()));

        $event = new Event(
            new EventType(EventType::BIRTHDAY),
            new Location(10, 10, 'admin1', 'admin2', 'ES'),
            new \DateTime('now')
        );
        $user = new User(new Email('valid@email.com'), new PersonalInformation('name', 'surname', 'phone'));
        $lead = $user->doLead($showroom, $event, new LeadInformationBag(['observations' => 'test']));


        $repo = new LeadRepository($em, $metadata);
        $repo->setMapper($leadMapper);
        $repo->setAsyncDispatcher($asyncDispatcher);
        $repo->save($lead);
        $this->assertEquals(1, $lead->getId());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testObjectNotLead()
    {
        $object = new \StdClass();
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $metadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()->getMock();
        $repo = new LeadRepository($em, $metadata);
        $repo->save($object);
    }
}
