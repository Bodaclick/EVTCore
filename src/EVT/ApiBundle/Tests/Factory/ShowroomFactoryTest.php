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

        $factory = new ShowroomFactory($verticalRepo, $providerRepo, $showroomRepo);
        $factory->createShowroom('fiestasclick.com', 1, 1);
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

        $factory = new ShowroomFactory($verticalRepo, $providerRepo, $showroomRepo);
        $factory->createShowroom('fiestasclick.com', 1, 1);

    }
}
