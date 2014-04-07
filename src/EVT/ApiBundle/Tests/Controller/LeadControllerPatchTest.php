<?php

namespace EVT\ApiBundle\Tests\Controller;

use EVT\CoreDomain\Provider\ShowroomType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;
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

/**
 * LeadControllerPatchTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class LeadControllerPatchTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;
    protected $header;

    public function testLeadRead()
    {
        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/x-www-form-urlencoded', 'Accept' => 'application/json'];

        $showroom = new Showroom(
            new Provider(
                new ProviderId(1),
                'providername',
                new EmailCollection(
                    new Email('valid2@email.com')
                ),
                'es_ES'
            ),
            new Vertical('test.com', 'es_ES', 'Europe/Madrid'),
            new ShowroomType(ShowroomType::FREE)
        );

        $rflShowroom = new \ReflectionClass($showroom);
        $rflId = $rflShowroom->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($showroom, 1);

        $lead1 = new Lead(
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

        $leadRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\LeadRepository')
            ->disableOriginalConstructor()->getMock();
        $leadRepo->expects($this->once())
            ->method('findByIdOwner')
            ->will($this->returnValue($lead1));
        $leadRepo->expects($this->once())
            ->method('save')
            ->will(
                $this->returnvalue(true)
            );

        $this->client->getContainer()->set('evt.repository.lead', $leadRepo);

        $this->assertNull($lead1->getReadAt());
        $this->client->request(
            'PATCH',
            '/api/leads/1/read?apikey=apikeyValue',
            [],
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_ACCEPTED, $this->client->getResponse()->getStatusCode());
        $this->assertNotNull($lead1->getReadAt());
    }
}
