<?php

namespace EVT\CoreDomainBundle\Test\Mapping;

use EVT\CoreDomain\Lead\Location;
use EVT\CoreDomainBundle\Entity\GenericUser;
use EVT\CoreDomainBundle\Mapping\ProviderMapping;
use EVT\CoreDomain\Email;
use EVT\CoreDomain\EmailCollection;

/**
 * ProviderMappingTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @author    Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class ProviderMappingTest extends \PHPUnit_Framework_TestCase
{
    public function testDomainToEntity()
    {
        $genericUser = $this->getMockBuilder('EVT\CoreDomainBundle\Entity\GenericUser')
            ->disableOriginalConstructor()->getMock();

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $em->expects($this->once())->method('getReference')->will($this->returnValue($genericUser));

        $dProvider = $this->getMockBuilder('EVT\CoreDomain\Provider\Provider')->disableOriginalConstructor()
            ->getMock();
        $dProvider->expects($this->once())->method('getLocation')->will(
            $this->returnValue(new Location(10, 10, 'lvl1', 'lvl2', 'ES'))
        );

        $emails = new EmailCollection(new Email('provider@email.com'));
        $dProvider->expects($this->once())->method('getNotificationEmails')
            ->will($this->returnValue($emails));
        $dProvider->expects($this->exactly(2))->method('getId')->will($this->returnValue(1));

        $manager = $this->getMockBuilder('EVT\CoreDomain\User\Manager')->disableOriginalConstructor()->getMock();
        $manager->expects($this->any())->method('getEmail')->will($this->returnValue('email@valid.com'));
        $manager->expects($this->once())->method('getId')->will($this->returnValue(1));
        $userMapper = $this->getMockBuilder('EVT\CoreDomainBundle\Mapping\UserMapping')
            ->disableOriginalConstructor()->getMock();

        $dProvider->expects($this->once())->method('getManagers')->will(
            $this->returnValue(
                new \ArrayObject([$manager])
            )
        );


        $mapper = new ProviderMapping($em, $userMapper);

        $eProvider = $mapper->mapDomainToEntity($dProvider);

        $this->assertInstanceOf('EVT\CoreDomainBundle\Entity\Provider', $eProvider);
        $this->assertCount(1, $eProvider->getGenericUser());
    }

    public function testEntityToDomain()
    {
        $manager = $this->getMockBuilder('EVT\CoreDomain\User\Manager')
            ->disableOriginalConstructor()->getMock();
        $manager->expects($this->any())->method('getEmail')->will($this->returnValue('valid@email.com'));

        $userMapper = $this->getMockBuilder('EVT\CoreDomainBundle\Mapping\UserMapping')
            ->disableOriginalConstructor()->setMethods(['mapEntityToDomain'])->getMock();
        $userMapper->expects($this->once())->method('mapEntityToDomain')->will($this->returnValue($manager));

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();

        $eProvider = $this->getMock('EVT\CoreDomainBundle\Entity\Provider');
        $eProvider->expects($this->once())->method('getId')->will($this->returnValue(1));
        $eProvider->expects($this->once())->method('getName')->will($this->returnValue('testName'));
        $eProvider->expects($this->once())->method('getNotificationEmails')
            ->will($this->returnValue(['valid@email.com']));
        $eProvider->expects($this->once())->method('getGenericUser')->will($this->returnValue([new GenericUser()]));
        $eProvider->expects($this->once())->method('getLocationLat')->will($this->returnValue(1));
        $eProvider->expects($this->once())->method('getLocationLong')->will($this->returnValue(1));
        $eProvider->expects($this->once())->method('getLocationAdminLevel1')->will($this->returnValue(1));
        $eProvider->expects($this->once())->method('getLocationAdminLevel2')->will($this->returnValue(1));
        $eProvider->expects($this->once())->method('getLocationCountry')->will($this->returnValue(1));

        $mapper = new ProviderMapping($em, $userMapper);

        $dProvider = $mapper->mapEntityToDomain($eProvider);

        $this->assertInstanceOf('EVT\CoreDomain\Provider\Provider', $dProvider);
    }
}
