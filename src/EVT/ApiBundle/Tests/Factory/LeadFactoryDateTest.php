<?php

namespace EVT\ApiBundle\Tests\Factory;

use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\User\User;
use EVT\ApiBundle\Factory\LeadFactory;

class LeadFactoryDateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testDateEmpty()
    {
        $lead= [
            'user' => [
                'name' => 'testUserName',
                'surname' => 'testUserSurname',
                'email' => 'valid@email.com',
                'phone' => '+34 0123456789'
            ],
            'event' => [
                'date' => '',
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

        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')
            ->disableOriginalConstructor()->getMock();

        $showroomRepo = $this->getMockBuilder('EVT\CoreDomain\Provider\ShowroomRepositoryInterface')
            ->disableOriginalConstructor()->getMock();

        $showroomRepo->expects($this->once())
            ->method('findOneById')
            ->will(
                $this->returnValue($showroom)
            );

        $leadRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\LeadRepository')
            ->disableOriginalConstructor()->getMock();

        $logger = $this->getMockBuilder('Symfony\Component\HttpKernel\Log\LoggerInterface')
            ->disableOriginalConstructor()->getMock();

        $factory = new LeadFactory($showroomRepo, $leadRepo, $logger);
        $factory->createLead(new User('valid@email.com', new PersonalInformation('a', 'b', 'c')), $lead);
    }
}
