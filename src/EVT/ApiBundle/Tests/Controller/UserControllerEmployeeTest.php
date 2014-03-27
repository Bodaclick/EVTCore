<?php

namespace EVT\ApiBundle\Tests\Controller;

use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;
use EVT\CoreDomainBundle\Entity\GenericUser as User;

/**
 * LeadControllerTest
 *
 * @author    Daniel Jimenez Tellez <djimenez@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class UserControllerEmployeeTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;
    protected $header;

    /**
     * Create a client to test request and mock services
     */
    public function setUp()
    {
        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/x-www-form-urlencoded', 'HTTP_ACCEPT' => 'application/json'];
    }

    public function mockContainerManager()
    {
        $user = new User();
        $user->setName('demoName');
        $user->setEmail('demo@demo.com');

        $userManager = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\UserRepository')
            ->disableOriginalConstructor()->getMock();

        $userManager->expects($this->once())->method('getManagerByUsername')->will($this->returnValue(null));
        $userManager->expects($this->once())->method('getEmployeeByUsername')->will($this->returnValue($user));

        $this->client->getContainer()->set('evt.repository.user', $userManager);
    }

    public function testUserEmployee()
    {
        $this->mockContainerManager();
        $this->client->request('GET', '/api/users/demo@demo.com?apikey=apikeyValue', [], [], $this->header);
        $this->assertEquals(
            Codes::HTTP_OK,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        $arrayEmployee = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('demoName', $arrayEmployee['name']);
        $this->assertEquals('demo@demo.com', $arrayEmployee['email']);
    }
}
