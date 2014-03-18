<?php

namespace EVT\ApiBundle\Tests\Controller;

use EVT\CoreDomain\Provider\ShowroomType;
use EVT\CoreDomain\Email;
use EVT\CoreDomain\EmailCollection;
use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\Lead\EventType;
use EVT\CoreDomain\Lead\Lead;
use EVT\CoreDomain\Lead\LeadId;
use EVT\CoreDomain\Lead\Location;
use EVT\CoreDomain\Provider\Provider;
use EVT\CoreDomain\Provider\ProviderId;
use EVT\CoreDomain\Provider\Showroom;
use EVT\CoreDomain\Provider\Vertical;
use EVT\CoreDomain\User\PersonalInformation;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * LeadControllerGetTest
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class LeadControllerGetTest extends WebTestCase
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
    }

    public function mockData ()
    {
        $showroom = new Showroom(
            new Provider(
                new ProviderId(1),
                'providername',
                new EmailCollection(
                    new Email('valid2@email.com')
                )
            ),
            new Vertical('test.com'),
            new ShowroomType(ShowroomType::FREE)
        );

        $rflShowroom = new \ReflectionClass($showroom);
        $rflId = $rflShowroom->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($showroom, 1);

        $lead = new Lead(
            new LeadId(1),
            new PersonalInformation('pepe', 'potamo', '910000000'),
            new Email('valid@email.com'),
            $showroom,
            new Event(
                new EventType(EventType::BIRTHDAY),
                new Location(10, 10, 'Parla', 'Madrid', 'España'),
                new \DateTime('2014-01-01 13:00:01', new \DateTimeZone('UTC'))
            )
        );

        return $lead;
    }

    public function mockLeadsContainer($method, $lead1)
    {
        $leadRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\LeadRepository')
            ->disableOriginalConstructor()->getMock();
        $leadRepo->expects($this->once())
            ->method($method)
            ->will($this->returnvalue($lead1));

        $this->client->getContainer()->set('evt.repository.lead', $leadRepo);
    }

    public function testGetLeads()
    {
        $this->mockLeadsContainer('findByOwner', [$this->mockData()]);
        $this->client->request(
            'GET',
            '/api/leads?apikey=apikeyValue',
            [],
            [],
            ['Content-Type' => 'application/json', 'HTTP_ACCEPT' => 'application/json']
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $arrayLeads = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('items', $arrayLeads);
        $this->assertArrayHasKey('pagination', $arrayLeads);
        $this->assertCount(1, ['items']);
        $this->assertEquals('valid@email.com', $arrayLeads['items'][0]['email']['email']);
        $this->assertEquals('2014-01-01CET14:00:01+0100', $arrayLeads['items'][0]['event']['date']);
    }

    public function testGetLead()
    {
        $this->mockLeadsContainer('findByIdOwner', $this->mockData());
        $this->client->request(
            'GET',
            '/api/leads/1?apikey=apikeyValue',
            [],
            [],
            ['Content-Type' => 'application/json', 'HTTP_ACCEPT' => 'application/json']
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $lead = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('event', $lead);
        $this->assertEquals('valid@email.com', $lead['email']['email']);
        $this->assertEquals('1', $lead['id']);
    }
}
