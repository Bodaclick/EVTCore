<?php

namespace EVT\ApiBundle\Tests\Factory;

use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\User\User;
use EVT\ApiBundle\Factory\LeadFactory;

class LeadFactoryTwinTest extends \PHPUnit_Framework_TestCase
{
    private $showroomRepo;
    private $leadRepo;
    private $logger;
    private $originalLead;

    public function testLeadCreationOkTwins()
    {
        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')
            ->disableOriginalConstructor()->getMock();

        $this->showroomRepo = $this->getMockBuilder('EVT\CoreDomain\Provider\ShowroomRepositoryInterface')
            ->disableOriginalConstructor()->getMock();

        $this->showroomRepo->expects($this->exactly(2))
            ->method('findShowroom')
            ->will(
                $this->returnValue($showroom)
            );

        $this->leadRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\LeadRepository')
            ->disableOriginalConstructor()->getMock();
        $this->leadRepo->expects($this->once())
            ->method('save')
            ->will(
                $this->returnvalue(true)
            );

        $this->logger = $this->getMockBuilder('Symfony\Component\HttpKernel\Log\LoggerInterface')
            ->disableOriginalConstructor()->getMock();

        $lead= [
            'user' => [
                'name' => 'testUserName',
                'surname' => 'testUserSurname',
                'email' => 'valid@email.com',
                'phone' => '+34 0123456789'
            ],
            'event' => [
                'date' => '2015/12/31',
                'type' => '1',
                'location' => [
                   'lat' => 10,
                   'long' => 10,
                   'admin_level_1' => 'Getafe',
                   'admin_level_2' => 'Madrid',
                   'country' => 'Spain'
                ]
            ],
            'showroom' => [
                'id' => '1'
            ]
        ];

        $factory = new LeadFactory($this->showroomRepo, $this->leadRepo, $this->logger);
        $lead1 = $factory->createLead(new User('valid@email.com', new PersonalInformation('a', 'b', 'c')), $lead);

        $this->leadRepo->expects($this->once())
            ->method('findByShowroomEmailSeconds')
            ->will(
                $this->returnValue([$lead1])
            );

        $lead2 = $factory->createLead(new User('valid@email.com', new PersonalInformation('a', 'b', 'c')), $lead);
        $this->assertEquals($lead1, $lead2);
    }
}
