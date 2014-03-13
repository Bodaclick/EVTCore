<?php

namespace EVT\CoreDomainBundle\Test\Functional\Repository;

use EVT\CoreDomain\Email;
use EVT\CoreDomain\Lead\Lead;
use EVT\CoreDomain\Lead\LeadId;
use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\Lead\EventType;
use EVT\CoreDomain\Lead\Location;
use EVT\CoreDomain\User\PersonalInformation;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * LeadRepositoryTest
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class LeadRepositoryTest extends WebTestCase
{
    private $repo;

    public function setUp()
    {
        $classes = [
            'EVT\ApiBundle\Tests\DataFixtures\ORM\LoadLeadData',
        ];
        $this->loadFixtures($classes);
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->repo = static::$kernel->getContainer()
            ->get('evt.repository.lead')
        ;
    }

    public function testFindByIdOwner()
    {
        $lead = $this->repo->findByIdOwner(1, 'usernameManager');

        $this->assertInstanceOf('EVT\CoreDomain\Lead\Lead', $lead);
        $this->assertEquals('valid@email.com', $lead->getEmail()->getEmail());
    }

    public function providerLead()
    {
        return [
            [1, 'username'],
            [1, 'usernameManager2'],
            [5, 'usernameManager'],
        ];
    }

    /**
     * @dataProvider providerLead
     */
    public function testFindByIdOwnerKO($id, $username)
    {
        $lead = $this->repo->findByIdOwner($id, $username);

        $this->assertNull($lead);
    }

    public function testFindByOwner()
    {
        $leads = $this->repo->findByOwner('usernameManager');

        $this->assertCount(2, $leads);
        $this->assertInstanceOf('EVT\CoreDomain\Lead\Lead', $leads[0]);
        $this->assertEquals('valid@email.com', $leads[0]->getEmail()->getEmail());
    }

    public function providerLeads()
    {
        return [
            ['username'],
            ['usernameManager2']
        ];
    }

    /**
     * @dataProvider providerLeads
     */
    public function testFindByOwnerKO($username)
    {
        $lead = $this->repo->findByOwner($username);

        $this->assertNull($lead);
    }

    public function testSaveCreate()
    {
        $repoShowroom = static::$kernel->getContainer()
            ->get('evt.repository.showroom');

        $email = new Email('email@mail.com');
        $personalInfo = new PersonalInformation('a', 'b', 'c');
        $showroom = $repoShowroom->findOneById(1);
        $event = new Event(
            new EventType(EventType::BIRTHDAY),
            new Location(0, -10.6754, 'Madrid', 'Madrid', 'Spain'),
            new \DateTime('now')
        );

        $lead = new Lead(new LeadId(''), $personalInfo, $email, $showroom, $event);
        $numLeads = $this->repo->count();

        $this->repo->save($lead);
        $leadCheck = $this->repo->findByIdOwner($lead->getId(), 'usernameManager');

        $this->assertInstanceOf('EVT\CoreDomain\Lead\Lead', $leadCheck);
        $this->assertEquals('email@mail.com', $leadCheck->getEmail()->getEmail());
        $this->assertEquals(($numLeads + 1), $this->repo->count());
        $this->assertEquals(0, $leadCheck->getEvent()->getLocation()->getLatLong()['lat']);
        $this->assertEquals(-10.6754, $leadCheck->getEvent()->getLocation()->getLatLong()['long']);
    }

    public function testSaveUpdate()
    {
        $lead = $this->repo->findByIdOwner(1, 'usernameManager');
        $oldDate = $lead->getReadAt();
        $lead->read();

        $numLeads = $this->repo->count();

        $this->repo->save($lead);

        $lead = $this->repo->findByIdOwner(1, 'usernameManager');

        $this->assertInstanceOf('EVT\CoreDomain\Lead\Lead', $lead);
        $this->assertNotEquals($oldDate, $lead->getReadAt());
        $this->assertEquals($numLeads, $this->repo->count());
    }

    public function testGetLastLeadByEmail()
    {
        $lead = $this->repo->getLastLeadByEmail('valid@email.com');

        $this->assertInstanceOf('EVT\CoreDomain\Lead\Lead', $lead);
        $this->assertEquals('valid@email.com', $lead->getEmail()->getEmail());
        $this->assertEquals(2, $lead->getId());
        $this->assertEquals(new \DateTime('2013-11-11 00:00:00'), $lead->getCreatedAt());
    }

    public function testGetLastLeadByEmailNoExistsEmail()
    {
        $lead = $this->repo->getLastLeadByEmail('noexiste@email.com');

        $this->assertNull($lead);
    }

    public function testCount()
    {
        $this->assertEquals(2, $this->repo->count());
    }

    public function testFindByShowroomEmailSeconds()
    {
        $repoShowroom = static::$kernel->getContainer()
            ->get('evt.repository.showroom');

        $email = new Email('rare@mail.com');
        $personalInfo = new PersonalInformation('a', 'b', 'c');
        $showroom = $repoShowroom->findOneById(1);
        $event = new Event(
            new EventType(EventType::BIRTHDAY),
            new Location(10, 10, 'Madrid', 'Madrid', 'Spain'),
            new \DateTime('now')
        );

        $lead = new Lead(new LeadId(''), $personalInfo, $email, $showroom, $event);

        $this->repo->save($lead);

        $leads = $this->repo->findByShowroomEmailSeconds($showroom, 'rare@mail.com', 60);

        $this->assertCount(1, $leads);
        $this->assertEquals('rare@mail.com', $leads[0]->getEmail()->getEmail());
    }
}
