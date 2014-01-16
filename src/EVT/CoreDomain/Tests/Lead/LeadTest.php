<?php

namespace EVT\CoreDomain\Tests\Lead;

use EVT\CoreDomain\Lead\LeadId;
use EVT\CoreDomain\Lead\Lead;
use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\Lead\EventType;
use EVT\CoreDomain\Lead\Location;
use EVT\CoreDomain\Lead\LeadInformationBag;
use EVT\CoreDomain\User\PersonalInformation;

class LeadTest extends \PHPUnit_Framework_TestCase
{
    public function testLeadCreation()
    {
        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()->getMock();
        $event = $this->getMockBuilder('EVT\CoreDomain\Lead\Event')->disableOriginalConstructor()->getMock();
        $personalInfo = new PersonalInformation();
        $lead = new Lead(new LeadId(''), $personalInfo, $showroom, $event);
        $this->assertEquals('', $lead->getId());
        $this->assertEquals($event, $lead->getEvent());
        $this->assertEquals($personalInfo, $lead->getPersonalInformation());
        $this->assertEquals($showroom, $lead->getShowroom());
        $this->assertNotNull($lead->getCreatedAt());
        $this->assertEquals('UTC', $lead->getCreatedAt()->getTimeZone()->getName());
    }

    public function testLeadInformationBag()
    {
        $personalInfo = new PersonalInformation();
        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()->getMock();
        $event = new Event(
            new EventType('birthday'),
            new Location(10, 10, 'Madrid', 'Madrid', 'Spain'),
            new \DateTime('now')
        );
        $lead = new Lead(new LeadId(''), $personalInfo, $showroom, $event);
        $informationBag = new LeadInformationBag();
        $lead->setInformationBag($informationBag);
        $this->assertEquals($informationBag, $lead->getInformationBag());
        $this->assertInstanceOf('EVT\CoreDomain\Lead\LeadInformationBag', $lead->getInformationBag());
    }
}
