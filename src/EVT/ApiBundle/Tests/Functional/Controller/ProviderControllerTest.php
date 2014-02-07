<?php

namespace EVT\ApiBundle\Tests\Functional\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 * @group Functional
 */
class ProviderControllerTest extends WebTestCase
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
            [
                'EVT\ApiBundle\Tests\DataFixtures\ORM\LoadUserData'
            ]
        );

    }

    public function testCreate()
    {
        $params = [
            'provider' => [
                'genericUser' => [1],
                'name' => 'providerName',
                'phone' => '915555555',
                'slug' => 'providerSlug',
                'locationAdminLevel1' => 'asdf',
                'locationAdminLevel2' => 'asdf',
                'locationCountry' => 'asdf',
                'locationLat' => 10,
                'locationLong' => 10,
                'notificationEmails' => ['valid@email.com', 'valid2@email.com']
            ]
        ];

        $this->client->request(
            'POST',
            '/api/providers?apikey=apikeyValue',
            $params,
            [],
            $this->header
        );

        $this->assertEquals(
            Codes::HTTP_CREATED,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
        $id = explode(
            '?',
            explode('/', json_decode($this->client->getResponse()->getContent(), true)['provider'])[3]
        )[0];
        $this->assertEquals(1, $id);
        $eProvider = $this->getContainer()->get('evt.repository.provider')->findOneById($id);
        $this->assertNotNull($eProvider);
        $this->assertEquals('providerName', $eProvider->getName());
        $this->assertEquals('providername', $eProvider->getSlug());
        $this->assertEquals('915555555', $eProvider->getPhone());
        $this->assertCount(2, $eProvider->getNotificationEmails());
    }

    public function provider()
    {
        return [
            [
                [
                    'provider' => [
                        'genericUser' => [2],
                        'name' => 'providerName',
                        'phone' => 'asdf',
                        'slug' => 'providerSlug',
                        'locationAdminLevel1' => 'asdf',
                        'locationAdminLevel2' => 'asdf',
                        'locationCountry' => 'asdf',
                        'locationLat' => 10,
                        'locationLong' => 10,
                        'notificationEmails' => 'valid@email.com'
                    ]
                ],
                [
                    'provider' => [
                        'genericUser' => [1],
                        'phone' => 'asdf',
                        'slug' => 'providerSlug',
                        'locationAdminLevel1' => '',
                        'locationAdminLevel2' => '',
                        'locationCountry' => 'asdf',
                        'locationLat' => 10,
                        'locationLong' => 10,
                        'notificationEmails' => 'valid@email.com'
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider provider
     */
    public function testWrongData($params)
    {
        $this->client->request(
            'POST',
            '/api/providers?apikey=apikeyValue',
            $params,
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }
}
