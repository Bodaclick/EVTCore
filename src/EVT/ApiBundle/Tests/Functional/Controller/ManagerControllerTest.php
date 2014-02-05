<?php

namespace EVT\ApiBundle\Tests\Functional\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 * @group Functional
 */
class ManagerControllerTest extends WebTestCase
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
        $this->loadFixtures(
            ['EVT\ApiBundle\Tests\DataFixtures\ORM\LoadShowroomData']
        );

    }

    public function testCreate()
    {
        $params = [
            'user' =>  [
                'email' => 'valid@email.com',
                'username' => 'username_manager',
                'plainPassword' => ['first' => '1234', 'second' => '1234']
            ]
        ];

        $this->client->request('POST', '/api/managers?apikey=apikeyValue', $params, [], $this->header);

        $this->assertEquals(Codes::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $id = explode(
            '?',
            explode('/', json_decode($this->client->getResponse()->getContent(), true)['manager'])[3]
        )[0];
        $this->assertEquals(1, $id);
        $eUser = $this->getContainer()->get('doctrine.orm.default_entity_manager')
            ->getRepository('EVTCoreDomainBundle:GenericUser')
            ->findOneById($id);
        $this->assertNotNull($eUser);
        $this->assertTrue(false !== array_search('ROLE_MANAGER', $eUser->getRoles()));
    }

    public function testDuplicate()
    {
        $params = [
            'user' =>  [
                'email' => 'valid@email.com',
                'username' => 'username_manager',
                'plainPassword' => ['first' => '1234', 'second' => '1234']
            ]
        ];

        $this->client->request('POST', '/api/managers?apikey=apikeyValue', $params, [], $this->header);
        $this->client->request('POST', '/api/managers?apikey=apikeyValue', $params, [], $this->header);

        $this->assertEquals(Codes::HTTP_CONFLICT, $this->client->getResponse()->getStatusCode());
    }
}
