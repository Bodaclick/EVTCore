<?php
namespace EVT\ApiBundle\Tests\Functional\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * ShowroomControllerGetTest
 *
 * @author    Alvaro Prudencio <aprudencio@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class ShowroomControllerGetTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;
    protected $header;

    /**
     * Create a client to test request
     */
    public function setUp()
    {
        $classes = [
            'EVT\ApiBundle\Tests\DataFixtures\ORM\LoadShowroomData',
        ];
        $this->loadFixtures($classes);
        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/json', 'HTTP_ACCEPT' => 'application/json'];

    }

    public function testGetShowrooms()
    {
        $this->client->request(
            'GET',
            '/api/showrooms?apikey=apikeyValue&canView=usernameManager',
            [],
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $arrayShowrooms = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('items', $arrayShowrooms);
        $this->assertArrayHasKey('pagination', $arrayShowrooms);
        $this->assertCount(2, $arrayShowrooms['items']);
        $this->assertEquals('valid@email.com', $arrayShowrooms['items'][0]['provider']['notification_emails']);
        $this->assertEquals('FREE', $arrayShowrooms['items'][0]['type']['name']);
        $this->assertEquals(1, $arrayShowrooms['pagination']['total_pages']);
        $this->assertEquals(1, $arrayShowrooms['pagination']['current_page']);
        $this->assertEquals(10, $arrayShowrooms['pagination']['items_per_page']);
        $this->assertEquals(2, $arrayShowrooms['pagination']['total_items']);
    }

}
