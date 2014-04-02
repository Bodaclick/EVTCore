<?php

namespace EVT\CoreDomainBundle\Test\Factory;

use EVT\CoreDomainBundle\Factory\PaginatorFactory;
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

 /**
 * PaginatorFactoryTest
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class PaginatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function mockDataLead()
    {
        $showroom = new Showroom(
            new Provider(
                new ProviderId(1),
                'providername',
                new EmailCollection(
                    new Email('valid2@email.com')
                ),
                'es_ES'
            ),
            new Vertical('test.com', 'es_ES'),
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

    public function testCreate()
    {
        $mockPagination = $this->getMockBuilder('Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination')
            ->disableOriginalConstructor()->getMock();
        $mockPagination->expects($this->once())
            ->method('getCurrentPageNumber')
            ->will($this->returnvalue(3));
        $mockPagination->expects($this->once())
            ->method('getItemNumberPerPage')
            ->will($this->returnvalue(10));
        $mockPagination->expects($this->once())
            ->method('getTotalItemCount')
            ->will($this->returnvalue(25));

        $paginator = PaginatorFactory::create($mockPagination, [$this->mockDataLead()]);

        $this->assertInstanceOf('EVT\CoreDomainBundle\Model\Paginator', $paginator);
    }
}
