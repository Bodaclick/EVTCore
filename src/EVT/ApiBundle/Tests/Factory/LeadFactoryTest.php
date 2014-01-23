<?php

namespace EVT\ApiBundle\Tests\Factory;

use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\User\User;
use EVT\ApiBundle\Factory\LeadFactory;

class LeadFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $showroomRepo;
    private $leadRepo;
    private $logger;

    public function setUp()
    {
        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')
            ->disableOriginalConstructor()->getMock();

        $this->showroomRepo = $this->getMockBuilder('EVT\CoreDomain\Provider\ShowroomRepositoryInterface')
            ->disableOriginalConstructor()->getMock();

        $this->showroomRepo->expects($this->once())
            ->method('find')
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
    }

    public function tearDown()
    {
        $this->showroomRepo = null;
        $this->leadRepo = null;
    }

    public function testLeadCreationOk()
    {
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
                ],
            'info' => [
                'observations' => 'test'
            ]
        ];

        $factory = new LeadFactory($this->showroomRepo, $this->leadRepo, $this->logger);
        $lead = $factory->createLead(new User('valid@email.com', new PersonalInformation('a', 'b', 'c')), $lead);
    }

    public function testLeadCreationFail()
    {
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
            ],
            'info' => [
                'observations' => 'test'
            ]
        ];

        $factory = new LeadFactory($this->showroomRepo, $this->leadRepo, $this->logger);
        $lead = $factory->createLead(new User('valid@email.com', new PersonalInformation('a', 'b', 'c')), $lead);
    }
}
