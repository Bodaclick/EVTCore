<?php

namespace EVT\ApiBundle\Tests\Functional\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

class ResetPasswordControllerTest extends WebTestCase
{
    protected $client;
    protected $header;
    private $em;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/x-www-form-urlencoded', 'HTTP_ACCEPT' => 'application/json'];
        $this->loadFixtures(
            [
                'EVT\ApiBundle\Tests\DataFixtures\ORM\LoadUserData'
            ]
        );

        $this->em = $this->client->getContainer()->get('doctrine')->getManager();
    }

    public function testResetPassword()
    {
        $oldPass = $this->getUserPassword();

        $this->client->request(
            'GET',
            '/api/resets/usernameManager/password?apikey=apikeyValue',
            [],
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $arrayContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('passwd', $arrayContent);
        $this->assertNotEquals('passManager',$arrayContent['passwd']);
        $nwPass = $this->getUserPassword();
        $this->assertNotEquals($oldPass,$nwPass);
    }

    private function getUserPassword()
    {
        $this->client->request(
            'GET',
            '/api/users/usernameManager?apikey=apikeyValue',
            [],
            [],
            $this->header
        );
        $arrayContent = json_decode($this->client->getResponse()->getContent(), true);
        return $arrayContent['password'];
    }
}
