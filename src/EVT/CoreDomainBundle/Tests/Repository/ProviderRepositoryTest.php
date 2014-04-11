<?php

namespace EVT\CoreDomainBundle\Test\Repository;

use EVT\CoreDomain\Email;
use EVT\CoreDomain\EmailCollection;
use EVT\CoreDomain\Provider\ProviderId;
use EVT\CoreDomain\Provider\Provider;
use EVT\CoreDomainBundle\Repository\ProviderRepository;
use EVT\CoreDomainBundle\Entity\Provider as EntityProvider;

/**
 * ProviderRepositoryTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class ProviderRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testSaveProvider()
    {
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $metadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()->getMock();
        $em->expects($this->once())->method('flush')->will($this->returnValue(null));
        $em->expects($this->any())->method('persist')->will(
            $this->returnCallback(
                function ($entity) {
                    $rflUser = new \ReflectionClass($entity);
                    $rflId = $rflUser->getProperty('id');
                    $rflId->setAccessible(true);
                    $rflId->setValue($entity, 1);
                }
            )
        );

        $eProvider = new EntityProvider();

        $providerMapping = $this->getMockBuilder('EVT\CoreDomainBundle\Mapping\ProviderMapping')
            ->disableOriginalConstructor()->getMock();
        $providerMapping->expects($this->once())->method('mapDomainToEntity')->will($this->returnValue($eProvider));

        $dProvider = new Provider(
            new ProviderId(null),
            'nameProvider',
            new EmailCollection(new Email('valid@email.com')),
            'es_ES'
        );
        $dProvider->setPhone('9876543210');

        $manager = $this->getMockBuilder('EVT\CoreDomain\User\Manager')->disableOriginalConstructor()->getMock();
        $manager->expects($this->any())->method('getEmail')->will($this->returnValue('email@valid.com'));

        $dProvider->addManager($manager);

        $repo = new ProviderRepository($em, $metadata);
        $repo->setMapper($providerMapping);
        $repo->save($dProvider);

        $this->assertEquals(1, $dProvider->getId());
    }
}
