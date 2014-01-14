<?php

namespace EVT\CoreDomain\Tests\Lead;

use EVT\CoreDomain\Lead\Lead;
use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\Lead\LeadInformationBag;

class LeadTest extends \PHPUnit_Framework_TestCase
{
    public function testLeadCreation()
    {
        $user = $this->getMockBuilder('EVT\CoreDomain\User\User')->disableOriginalConstructor()->getMock();
        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()->getMock();
        $event = $this->getMockBuilder('EVT\CoreDomain\Lead\Event')->disableOriginalConstructor()->getMock();
        $lead = new Lead($user, $showroom, $event);
        $this->assertEquals($event, $lead->getEvent());
        $this->assertEquals($user, $lead->getUser());
        $this->assertEquals($showroom, $lead->getShowroom());
    }

    public function testLeadInformationBag()
    {
        $user = $this->getMockBuilder('EVT\CoreDomain\User\User')->disableOriginalConstructor()->getMock();
        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()->getMock();
        $event = new Event(new \DateTime('now'));
        $lead = new Lead($user, $showroom, $event);
        $informationBag = new LeadInformationBag();
        $lead->setInformationBag($informationBag());
        $this->assertEquals($informationBag, $lead->getInformationBag());
        $this->assertInstanceOf('EVT\CoreDomain\Lead\LeadInformationBag', $lead->getInformationBag());
    }
}
