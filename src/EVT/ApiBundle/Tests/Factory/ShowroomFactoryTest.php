<?php

namespace EVT\ApiBundle\Tests\Factory;

use EVT\ApiBundle\Factory\ShowroomFactory;

/**
 * Class ShowroomFactoryTest
 * @package EVT\ApiBundle\Tests\Factory
 */
class ShowroomFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testShowroomCreate()
    {
        $verticalRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\VerticalRepository')
            ->disableOriginalConstructor()->getMock();
        $verticalMock = $this->getMockBuilder('EVT\CoreDomain\Provider\Vertical')->disableOriginalConstructor()
            ->getMock();
        $showroomMock = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()
            ->getMock();
        $verticalMock->expects($this->once())->method('addShowroom')->will($this->returnValue($showroomMock));
        $verticalRepo->expects($this->once())->method('findOneByDomain')->will($this->returnValue($verticalMock));
        $verticalRepo->expects($this->once())->method('findShowroom')->will($this->returnValue(false));

        $providerRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\ProviderRepository')
            ->disableOriginalConstructor()->getMock();
        $providerMock = $this->getMockBuilder('EVT\CoreDomain\Provider\Provider')->disableOriginalConstructor()
            ->getMock();
        $providerRepo->expects($this->once())->method('findOneById')->will($this->returnValue($providerMock));

        $showroomRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\ShowroomRepository')
            ->disableOriginalConstructor()->getMock();
        $showroomRepo->expects($this->once())->method('save')->will($this->returnValue(true));

        $syncEMD = $this->getMockBuilder('OldSound\RabbitMqBundle\RabbitMq\Producer')
            ->disableOriginalConstructor()->getMock();
        $syncEMD->expects($this->once())->method('publish');

        $serializer = $this->getMockBuilder('JMS\Serializer\Serializer')
            ->disableOriginalConstructor()->getMock();
        $serializer->expects($this->once())->method('serialize')->will($this->returnValue('{}'));

        $factory = new ShowroomFactory($verticalRepo, $providerRepo, $showroomRepo, $syncEMD, $serializer);
        $data = [
            'score' => 1,
            'vertical' => 'test.com',
            'provider' => 1,
            'name' => '',
            'url' => 'changed',
            'phone' => '999999999'
        ];
        $factory->createShowroom($data);
    }

    public function testShowroomExists()
    {
        $verticalRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\VerticalRepository')
            ->disableOriginalConstructor()->getMock();
        $verticalMock = $this->getMockBuilder('EVT\CoreDomain\Provider\Vertical')->disableOriginalConstructor()
            ->getMock();
        $showroomMock = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()
            ->getMock();
        $verticalMock->expects($this->never())->method('addShowroom')->will($this->returnValue($showroomMock));
        $verticalRepo->expects($this->once())->method('findOneByDomain')->will($this->returnValue($verticalMock));
        $verticalRepo->expects($this->once())->method('findShowroom')->will($this->returnValue($showroomMock));

        $providerRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\ProviderRepository')
            ->disableOriginalConstructor()->getMock();
        $providerMock = $this->getMockBuilder('EVT\CoreDomain\Provider\Provider')->disableOriginalConstructor()
            ->getMock();
        $providerRepo->expects($this->once())->method('findOneById')->will($this->returnValue($providerMock));

        $showroomRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\ShowroomRepository')
            ->disableOriginalConstructor()->getMock();
        $showroomRepo->expects($this->never())->method('save')->will($this->returnValue(true));

        $syncEMD = $this->getMockBuilder('OldSound\RabbitMqBundle\RabbitMq\Producer')
            ->disableOriginalConstructor()->getMock();
        $syncEMD->expects($this->never())->method('publish');

        $serializer = $this->getMockBuilder('JMS\Serializer\Serializer')
            ->disableOriginalConstructor()->getMock();

        $data = [
            'score' => 1,
            'vertical' => 'test.com',
            'provider' => 1,
            'name' => '',
            'url' => 'changed',
            'phone' => '999999999'
        ];

        $factory = new ShowroomFactory($verticalRepo, $providerRepo, $showroomRepo, $syncEMD, $serializer);
        $factory->createShowroom($data);
    }
}
