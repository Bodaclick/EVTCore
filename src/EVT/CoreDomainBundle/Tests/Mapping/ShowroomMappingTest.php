<?php

namespace EVT\CoreDomainBundle\Test\Mapping;


use EVT\CoreDomainBundle\Mapping\ShowroomMapping;
use EVT\CoreDomain\Provider\Showroom;
use EVT\CoreDomainBundle\Entity\Showroom as EShowroom;

class ShowroomMappingTest extends \PHPUnit_Framework_TestCase
{

    public function testDomainToEntityIsMapped()
    {
        $eProvider = $this->getMockBuilder('EVT\CoreDomainBundle\Entity\Provider')
            ->disableOriginalConstructor()->getMock();

        $eVertical = $this->getMockBuilder('EVT\CoreDomainBundle\Entity\Vertical')
            ->disableOriginalConstructor()->getMock();

        $dProvider = $this->getMockBuilder('EVT\CoreDomain\Provider\Provider')
            ->disableOriginalConstructor()->getMock();

        $dVertical = $this->getMockBuilder('EVT\CoreDomain\Provider\Vertical')
            ->disableOriginalConstructor()->getMock();

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $em->expects($this->at(0))->method('getReference')->will($this->returnValue($eProvider));
        $em->expects($this->at(1))->method('getReference')->will($this->returnValue($eVertical));
        $verticalMapper = $this->getMockBuilder('EVT\CoreDomainBundle\Mapping\VerticalMapping')
            ->disableOriginalConstructor()->getMock();
        $providerMapper = $this->getMockBuilder('EVT\CoreDomainBundle\Mapping\ProviderMapping')
            ->disableOriginalConstructor()->getMock();

        $dShowroom = new Showroom($dProvider, $dVertical, 0);

        $mapper = new ShowroomMapping($em, $providerMapper, $verticalMapper);
        $eShowroom = $mapper->mapDomainToEntity($dShowroom);

        $this->assertInstanceOf('EVT\CoreDomainBundle\Entity\Showroom', $eShowroom);
        $this->assertEquals($eShowroom->getId(), $eShowroom->getId());
        $this->assertEquals($eShowroom->getName(), $eShowroom->getName());
        $this->assertEquals($eShowroom->getPhone(), $eShowroom->getPhone());
        $this->assertEquals($eShowroom->getSlug(), $eShowroom->getSlug());
    }

    public function testEntityToDomainMap()
    {
        $dProvider = $this->getMockBuilder('EVT\CoreDomain\Provider\Provider')
            ->disableOriginalConstructor()->getMock();

        $dVertical = $this->getMockBuilder('EVT\CoreDomain\Provider\Vertical')
            ->disableOriginalConstructor()->getMock();

        $verticalMapper = $this->getMockBuilder('EVT\CoreDomainBundle\Mapping\VerticalMapping')
            ->disableOriginalConstructor()->getMock();
        $verticalMapper->expects($this->once())->method('mapEntityToDomain')->will($this->returnValue($dVertical));
        $providerMapper = $this->getMockBuilder('EVT\CoreDomainBundle\Mapping\ProviderMapping')
            ->disableOriginalConstructor()->getMock();
        $providerMapper->expects($this->once())->method('mapEntityToDomain')->will($this->returnValue($dProvider));

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();

        $eShowroom = new EShowroom();

        $mapper = new ShowroomMapping($em, $providerMapper, $verticalMapper);
        $dShowroom = $mapper->mapEntityToDomain($eShowroom);

        $this->assertInstanceOf('EVT\CoreDomain\Provider\Showroom', $dShowroom);
    }
}
