<?php

namespace EVT\ApiBundle\Tests\Factory;

use EVT\ApiBundle\Factory\ShowroomFactory;

/**
 * ShowroomFactoryTest
 *
 * @author    Quique Torras <etorras@gmail.com>
 * @copyright 2014 Bodaclick S.A
 */
class ShowroomFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testShowroomCreateOK()
    {
        $verticalRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\VerticalRepository')->disableOriginalConstructor()
            ->getMock();
        $verticalMock = $this->getMockBuilder('EVT\CoreDomain\Provider\Vertical')->disableOriginalConstructor()
            ->getMock();
        $showroomMock = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()
            ->getMock();
        $verticalMock->expects($this->once())->method('addShowroom')->will($this->returnValue($showroomMock));
        $verticalRepo->expects($this->once())->method('findVertical')->will($this->returnValue($verticalMock));

        $providerRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\ProviderRepository')->disableOriginalConstructor()
            ->getMock();
        $providerMock = $this->getMockBuilder('EVT\CoreDomain\Provider\Provider')->disableOriginalConstructor()
            ->getMock();
        $providerRepo->expects($this->once())->method('find')->will($this->returnValue($providerMock));

        $showroomRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\ShowroomRepository')->disableOriginalConstructor()
            ->getMock();
        $showroomRepo->expects($this->once())->method('save')->will($this->returnValue(true));

        $factory = new ShowroomFactory($verticalRepo, $providerRepo, $showroomRepo);
        $factory->createShowroom('fiestasclick.com',1,1);

    }
}
 