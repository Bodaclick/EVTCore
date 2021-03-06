<?php

namespace EVT\CoreDomain\Tests\User;

use EVT\CoreDomain\User\User;
use EVT\CoreDomain\Lead\LeadInformationBag;
use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\Email;

class UserDoesLeadTest extends \PHPUnit_Framework_TestCase
{
    public function testDoLead()
    {
        $email = new Email('email@mail.com');
        $user = new User($email, new PersonalInformation('a', 'b', 'c'));
        $event = $this->getMockBuilder('EVT\CoreDomain\Lead\Event')->disableOriginalConstructor()->getMock();
        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()->getMock();
        $informationBag = new LeadInformationBag(['key' => 'value']);
        $lead = $user->doLead($showroom, $event, $informationBag);
        $this->assertInstanceOf('EVT\CoreDomain\Lead\Lead', $lead);
        $this->assertEquals('value', $lead->getInformationBag()->get('key'));
        $this->assertEquals($showroom, $lead->getShowroom());
        $this->assertEquals($user->getEmail(), $lead->getEmail()->getEmail());
    }

    public function testDoLeadNoInfoBag()
    {
        $user = new User('email@mail.com', new PersonalInformation('a', 'b', 'c'));
        $event = $this->getMockBuilder('EVT\CoreDomain\Lead\Event')->disableOriginalConstructor()->getMock();
        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()->getMock();
        $lead = $user->doLead($showroom, $event);
        $this->assertInstanceOf('EVT\CoreDomain\Lead\Lead', $lead);
        $this->assertCount(0, $lead->getInformationBag());
    }
}
