<?php

namespace EVT\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * ProviderControllerTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
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

    }

    public function testCreateProvider()
    {
        $provider = $this->getMockBuilder('EVT\CoreDomainBundle\Entity\Provider')->disableOriginalConstructor()
            ->getMock();
        $provider->expects($this->once())->method('getId')->will($this->returnValue(1));

        $providerRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\Provider')->setMethods(['save'])
            ->disableOriginalConstructor()->getMock();
        $providerRepo->expects($this->once())->method('save')->will($this->returnValue($provider));

        $formMock = $this->getMockBuilder('EVT\CoreDomainBundle\Form\Type\ProviderFormType')
            ->setMethods(['isValid', 'getData', 'handleRequest'])->disableOriginalConstructor()->getMock();
        $formMock->expects($this->once())->method('isValid')->will($this->returnValue(true));
        $formMock->expects($this->once())->method('getData')->will($this->returnValue($provider));
        $formMock->expects($this->once())->method('handleRequest')->will($this->returnValue(true));

        $factoryMock = $this->getMockBuilder('Symfony\Component\Form\FormFactory')->disableOriginalConstructor()
            ->getMock();
        $factoryMock->expects($this->once())->method('create')->will($this->returnValue($formMock));

        $this->client->getContainer()->set('form.factory', $factoryMock);
        $this->client->getContainer()->set('evt.form.provider', $formMock);
        $this->client->getContainer()->set('evt.repository.provider', $providerRepo);

        $params = [
            'provider' => [
                'genericUser' => [1],
                'name' => 'dgfsdg',
                'phone' => 'asdf',
                'slug' => 'asdf',
                'locationAdminLevel1' => 'asdf',
                'locationAdminLevel2' => 'asdf',
                'locationCountry' => 'asdf',
                'locationLat' => 10,
                'locationLong' => 10
            ]
        ];

        $this->client->request(
            'POST',
            '/api/providers?apikey=apikeyValue',
            $params,
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('{"provider":"\/api\/providers\/1"}', $this->client->getResponse()->getContent());
    }

    public function testNewProvider()
    {
        $crawler = $this->client->request(
            'GET',
            '/api/providers/new?apikey=apikeyValue',
            [],
            []
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertCount(1, $crawler->filter('button'));
    }
}
