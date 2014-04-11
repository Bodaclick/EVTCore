<?php

namespace EVT\ApiBundle\Tests\Factory;

use EVT\ApiBundle\Factory\ProviderFactory;

class ProviderFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $providerRepo;
    private $userRepo;

    public function setUp()
    {
        $provider = $this->getMockBuilder('EVT\CoreDomain\Provider\Provider')
            ->disableOriginalConstructor()->getMock();

        $this->providerRepo = $this->getMockBuilder('EVT\CoreDomain\Provider\ProviderRepositoryInterface')
            ->disableOriginalConstructor()
            ->setMethods(['save', 'findExistingProvider', 'update', 'delete', 'findAll'])
            ->getMock();

        $this->providerRepo->expects($this->once())
            ->method('save')
            ->will(
                $this->returnValue($provider)
            );
        $this->providerRepo
            ->expects($this->once())->method('findExistingProvider')->will($this->returnValue(null));

        $manager = $this->getMockBuilder('EVT\CoreDomain\User\Manager')
            ->disableOriginalConstructor()->getMock();
        $manager->expects($this->any())
            ->method('getEmail')
            ->will(
                $this->returnValue('valid@email.com')
            );

        $this->userRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\UserRepository')
            ->disableOriginalConstructor()->getMock();

        $this->userRepo->expects($this->once())
            ->method('getManagerById')
            ->will(
                $this->returnValue($manager)
            );
    }

    public function tearDown()
    {
        $this->providerRepo = null;
    }

    public function testProviderCreationOk()
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
            'notificationEmails' => ['valid@email.com', 'valid2@email.com'],
            'lang' => 'es_ES'
        ];

        $factory = new ProviderFactory($this->providerRepo, $this->userRepo);
        $provider = $factory->createProvider($params);

        $this->assertEquals('providerName', $provider->getName());
        $notificationEmail = $provider->getNotificationEmails();
        $this->assertCount(2, $notificationEmail);
        $this->assertEquals('valid@email.com', $notificationEmail[0]);
        $this->assertEquals('valid2@email.com', $notificationEmail[1]);

        $managers = $provider->getManagers();
        $this->assertCount(1, $managers);
        $this->assertEquals('valid@email.com', $managers->getIterator()->current()->getEmail());
    }
}
