<?php

namespace EVT\CoreDomain\Tests\User;

use EVT\CoreDomain\User\User;
use EVT\CoreDomain\InformationBag;

class UserDoesLeadTest extends \PHPUnit_Framework_TestCase
{
    public function testDoLead()
    {
        $user = new User('name', 'email@mail.com');
        $event = $this->getMockBuilder('EVT\CoreDomain\Lead\Event')->disableOriginalConstructor()->getMock();
        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()->getMock();
        $informationBag = new InformationBag(['key' => 'value']);
        $lead = $user->doLead($showroom, $event, $informationBag);
        $this->assertInstanceOf('EVT\CoreDomain\Lead\Lead', $lead);
        $this->assertEquals('value', $lead->getInformationBag()->get('key'));
    }

    public function testDoLeadNoInfoBag()
    {
        $user = new User('name', 'email@mail.com');
        $event = $this->getMockBuilder('EVT\CoreDomain\Lead\Event')->disableOriginalConstructor()->getMock();
        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()->getMock();
        $lead = $user->doLead($showroom, $event);
        $this->assertInstanceOf('EVT\CoreDomain\Lead\Lead', $lead);
        $this->assertNull($lead->getInformationBag());
    }
}
