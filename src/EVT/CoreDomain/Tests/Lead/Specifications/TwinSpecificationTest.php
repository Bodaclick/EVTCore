<?php

namespace EVT\CoreDomain\Tests\Lead\Specifications;

use EVT\CoreDomain\Email;
use EVT\CoreDomain\Lead\Specifications\TwinSpecification;
use EVT\CoreDomain\Lead\LeadId;
use EVT\CoreDomain\Lead\Lead;
use EVT\CoreDomain\User\PersonalInformation;

/**
 * TwinSpecificationTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class TwinSpecificationTest extends \PHPUnit_Framework_TestCase
{
    private $showroom;
    private $event;
    private $personalInfo;
    private $email;

    public function setUp()
    {
        $this->showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()
            ->getMock();
        $this->event = $this->getMockBuilder('EVT\CoreDomain\Lead\Event')->disableOriginalConstructor()->getMock();
        $this->personalInfo = new PersonalInformation('a', 'b', 'c');
        $this->email = new Email('email@mail.com');
    }

    public function testGetTwin()
    {
        $originalLead = new Lead(new LeadId(''), $this->personalInfo, $this->email, $this->showroom, $this->event);

        $leadRepo = $this->getMockBuilder('EVT\CoreDomain\Lead\LeadRepositoryInterface')
            ->disableOriginalConstructor()->getMock();
        $leadRepo->expects($this->once())
            ->method('findByShowroomEmailSeconds')
            ->will(
                $this->returnValue([$originalLead])
            );

        $lead = new Lead(new LeadId(''), $this->personalInfo, $this->email, $this->showroom, $this->event);
        $isTwin = new TwinSpecification($leadRepo);

        $this->assertTrue($isTwin->isSatisfiedBy($lead));
        $this->assertEquals($originalLead, $isTwin->getTwin());
    }

    public function testGetNoTwin()
    {
        $leadRepo = $this->getMockBuilder('EVT\CoreDomain\Lead\LeadRepositoryInterface')
            ->disableOriginalConstructor()->getMock();
        $leadRepo->expects($this->once())
            ->method('findByShowroomEmailSeconds')
            ->will(
                $this->returnValue([])
            );

        $lead = new Lead(new LeadId(''), $this->personalInfo, $this->email, $this->showroom, $this->event);
        $isTwin = new TwinSpecification($leadRepo);

        $this->assertFalse($isTwin->isSatisfiedBy($lead));
        $this->assertNull($isTwin->getTwin());
    }
}
