<?php

namespace EVT\ApiBundle\Tests\Factory;

use EVT\ApiBundle\Factory\ProviderFactory;

class DuplicateProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testProviderExists()
    {
        $params = [
            'genericUser' => 1,
            'name' => 'providerName',
            'phone' => 'asdf',
            'slug' => 'providerSlug',
            'locationAdminLevel1' => 'asdf',
            'locationAdminLevel2' => 'asdf',
            'locationCountry' => 'asdf',
            'locationLat' => 10,
            'locationLong' => 10,
            'notificationEmails' => ['valid@email.com', 'valid2@email.com']
        ];
        $manager = $this->getMockBuilder('EVT\CoreDomain\User\Manager')
            ->disableOriginalConstructor()->getMock();
        $manager->expects($this->any())->method('getEmail')->will($this->returnValue('valid@email.com'));

        $userRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\UserRepository')
            ->disableOriginalConstructor()->getMock();

        $userRepo->expects($this->once())->method('getManagerById')->will($this->returnValue($manager));

        $provider = $this->getMockBuilder('EVT\CoreDomain\Provider\Provider')
            ->disableOriginalConstructor()->getMock();
        $providerRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\ProviderRepository')
            ->disableOriginalConstructor()->setMethods(['save', 'findExistingProvider'])->getMock();
        $providerRepo->expects($this->never())->method('save');
        $providerRepo->expects($this->once())->method('findExistingProvider')->will($this->returnValue($provider));

        $factory = new ProviderFactory($providerRepo, $userRepo);
        $factory->createProvider($params);
    }
}
