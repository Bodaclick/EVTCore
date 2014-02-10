<?php

namespace EVT\ApiBundle\Tests\Functional\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

class ShowroomControllerTest extends WebTestCase
{
    protected $client;
    protected $header;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/x-www-form-urlencoded', 'HTTP_ACCEPT' => 'application/json'];
        $this->loadFixtures(
            [
                'EVT\ApiBundle\Tests\DataFixtures\ORM\LoadVerticalData'
            ]
        );
    }
    public function testCreate()
    {
        $params = [
            'showroom' =>
            [
                'provider' => 1,
                'vertical' => 'test.com',
                'score' => 1,
                'name' => 'test vertical'
            ]
        ];

        $this->client->request(
            'POST',
            '/api/showrooms?apikey=apikeyValue',
            $params,
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $id = explode(
            '?',
            explode('/', json_decode($this->client->getResponse()->getContent(), true)['showroom'])[3]
        )[0];

        $this->assertEquals(1, $id);
        $eShowroom = $this->getContainer()->get('evt.repository.showroom')->findOneById($id);
        $this->assertNotNull($eShowroom);
        $this->assertEquals('1', $eShowroom->getProvider()->getId());
        $this->assertEquals('test.com', $eShowroom->getVertical()->getDomain());
        $this->assertEquals('1', $eShowroom->getScore());
        $this->assertEquals('test vertical', $eShowroom->getName());
    }

    public function wrongDataProvider()
    {
        return [
            [
                [
                    'provider' => 1,
                    'vertical' => 'noexiste.com',
                    'score' => 1
                ],
                [
                    'provider' => 2,
                    'vertical' => 'test.com',
                    'score' => 1

                ]
            ]
        ];
    }

    /**
     * @dataProvider wrongDataProvider
     */
    public function testWrongData($params)
    {
        $this->client->request(
            'POST',
            '/api/showrooms?apikey=apikeyValue',
            $params,
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }
}
