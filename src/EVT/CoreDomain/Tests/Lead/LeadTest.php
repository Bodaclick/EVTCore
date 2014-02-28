<?php

namespace EVT\CoreDomain\Tests\Lead;

use EVT\CoreDomain\Lead\LeadId;
use EVT\CoreDomain\Lead\Lead;
use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\Lead\EventType;
use EVT\CoreDomain\Lead\Location;
use EVT\CoreDomain\Lead\LeadInformationBag;
use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\Email;

class LeadTest extends \PHPUnit_Framework_TestCase
{
    public function testLeadCreation()
    {
        $email = new Email('email@mail.com');
        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()->getMock();
        $event = $this->getMockBuilder('EVT\CoreDomain\Lead\Event')->disableOriginalConstructor()->getMock();
        $personalInfo = new PersonalInformation('a', 'b', 'c');

        $lead = new Lead(new LeadId(''), $personalInfo, $email, $showroom, $event);

        $this->assertEquals('', $lead->getId());
        $this->assertEquals($event, $lead->getEvent());
        $this->assertEquals($personalInfo, $lead->getPersonalInformation('a', 'b', 'c'));
        $this->assertEquals($showroom, $lead->getShowroom());
        $this->assertNotNull($lead->getCreatedAt());
        $this->assertEquals('UTC', $lead->getCreatedAt()->getTimeZone()->getName());
    }

    public function testLeadInformationBag()
    {
        $email = new Email('email@mail.com');
        $personalInfo = new PersonalInformation('a', 'b', 'c');
        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()->getMock();
        $event = new Event(
            new EventType(EventType::BIRTHDAY),
            new Location(10, 10, 'Madrid', 'Madrid', 'Spain'),
            new \DateTime('now')
        );

        $lead = new Lead(new LeadId(''), $personalInfo, $email, $showroom, $event);

        $informationBag = new LeadInformationBag();
        $lead->setInformationBag($informationBag);
        $this->assertEquals($informationBag, $lead->getInformationBag());
        $this->assertInstanceOf('EVT\CoreDomain\Lead\LeadInformationBag', $lead->getInformationBag());
    }

    public function testLeadRead()
    {
        $email = new Email('email@mail.com');
        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()->getMock();
        $event = $this->getMockBuilder('EVT\CoreDomain\Lead\Event')->disableOriginalConstructor()->getMock();
        $personalInfo = new PersonalInformation('a', 'b', 'c');

        $lead = new Lead(new LeadId(''), $personalInfo, $email, $showroom, $event);

        $this->assertEquals($showroom, $lead->getShowroom());
        $this->assertNull($lead->getReadAt());
        $lead->read();
        $this->assertNotNull($lead->getReadAt());
        $this->assertEquals('UTC', $lead->getReadAt()->getTimeZone()->getName());
    }
}
