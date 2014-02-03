<?php

namespace EVT\CoreDomainBundle\Test\Mapping;

use EVT\CoreDomainBundle\Entity\GenericUser;
use EVT\CoreDomainBundle\Mapping\ProviderMapping;
use EVT\CoreDomain\Email;
use EVT\CoreDomain\EmailCollection;
use EVT\CoreDomain\Provider\ProviderId;
use EVT\CoreDomain\Provider\Provider;
use EVT\CoreDomainBundle\Entity\Provider as EProvider;

/**
 * ProviderMappingTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @author    Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class ProviderMappingTest extends \PHPUnit_Framework_TestCase
{
    public function testDomainToEntityIsMapped()
    {
        $genericUser = $this->getMockBuilder('EVT\CoreDomainBundle\Entity\GenericUser')
            ->disableOriginalConstructor()->getMock();

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $em->expects($this->once())->method('getReference')->will($this->returnValue($genericUser));

        $dProvider = new Provider(
            new ProviderId('123'),
            'nameProvider',
            new EmailCollection(new Email('valid@email.com'))
        );
        $dProvider->setPhone('9876543210');

        $manager = $this->getMockBuilder('EVT\CoreDomain\User\Manager')->disableOriginalConstructor()->getMock();
        $manager->expects($this->any())->method('getEmail')->will($this->returnValue('email@valid.com'));
        $manager->expects($this->once())->method('getId')->will($this->returnValue(1));
        $userMapper = $this->getMockBuilder('EVT\CoreDomainBundle\Mapping\UserMapping')
            ->disableOriginalConstructor()->getMock();

        $dProvider->addManager($manager);

        $mapper = new ProviderMapping($em, $userMapper);

        $eProvider = $mapper->mapDomainToEntity($dProvider);

        $this->assertInstanceOf('EVT\CoreDomainBundle\Entity\Provider', $eProvider);
        $this->assertEquals($dProvider->getId(), $eProvider->getId());
        $this->assertEquals($dProvider->getName(), $eProvider->getName());
        $this->assertEquals($dProvider->getSlug(), $eProvider->getSlug());
        $this->assertEquals($dProvider->getPhone(), $eProvider->getPhone());
        $managers = $eProvider->getGenericUser();
        $this->assertCount(1, $managers);
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

        $mapper = new ProviderMapping($em, $userMapper);

        $dProvider = $mapper->mapEntityToDomain($eProvider);

        $this->assertInstanceOf('EVT\CoreDomain\Provider\Provider', $dProvider);
    }
}
