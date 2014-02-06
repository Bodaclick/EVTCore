<?php


namespace EVT\CoreDomainBundle\Tests\Repository;

use EVT\CoreDomainBundle\Entity\Showroom;
use EVT\CoreDomainBundle\Repository\ShowroomRepository;
use EVT\CoreDomain\Provider\Showroom as DomainShowroom;

/**
 * Class ShowroomRepositoryTest
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class ShowroomRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testSaveCreate()
    {
        $provider = $this->getMockBuilder('EVT\CoreDomain\Provider\Provider')->disableOriginalConstructor()->getMock();
        $vertical = $this->getMockBuilder('EVT\CoreDomain\Provider\Vertical')->disableOriginalConstructor()->getMock();
        $domainMock = new DomainShowroom($provider, $vertical, 0);

        $srMapper = $this->getMockBuilder('EVT\CoreDomainBundle\Mapping\ShowroomMapping')
            ->disableOriginalConstructor()->getMock();
        $srMapper->expects($this->once())->method('mapDomainToEntity')->will($this->returnValue(new Showroom()));
        $emMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $emMock->expects($this->once())->method('persist')->will($this->returnSelf());
        $emMock->expects($this->once())->method('flush')->will($this->returnSelf());
        $metadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()->getMock();

        $asyncDispatcher = $this->getMockBuilder('BDK\AsyncDispatcherBundle\Model\EventDispatcher\AsyncEventDispatcher')
            ->disableOriginalConstructor()->getMock();

        $asyncDispatcher->expects($this->once())->method('dispatch')->will($this->returnValue($this->returnSelf()));

        $repo = new ShowroomRepository($emMock, $metadata);
        $repo->setMapper($srMapper);
        $repo->setAsyncDispatcher($asyncDispatcher);
        $repo->save($domainMock);
    }

    public function testSaveUpdate()
    {
        $provider = $this->getMockBuilder('EVT\CoreDomain\Provider\Provider')->disableOriginalConstructor()->getMock();
        $vertical = $this->getMockBuilder('EVT\CoreDomain\Provider\Vertical')->disableOriginalConstructor()->getMock();
        $domainMock = new DomainShowroom($provider, $vertical, 0);

        $srMapper = $this->getMockBuilder('EVT\CoreDomainBundle\Mapping\ShowroomMapping')
            ->disableOriginalConstructor()->getMock();
        $srMapper->expects($this->once())->method('mapDomainToEntity')->will($this->returnValue(new Showroom()));
        $emMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $emMock->expects($this->once())->method('persist')->will($this->returnSelf());
        $emMock->expects($this->once())->method('flush')->will($this->returnSelf());
        $metadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()->getMock();

        $asyncDispatcher = $this->getMockBuilder('BDK\AsyncDispatcherBundle\Model\EventDispatcher\AsyncEventDispatcher')
            ->disableOriginalConstructor()->getMock();

        $asyncDispatcher->expects($this->once())->method('dispatch')->will($this->returnValue($this->returnSelf()));

        $rflShowroom = new \ReflectionClass($domainMock);
        $rflId = $rflShowroom->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($domainMock, 1);

        $repo = new ShowroomRepository($emMock, $metadata);
        $repo->setMapper($srMapper);
        $repo->setAsyncDispatcher($asyncDispatcher);
        $repo->save($domainMock);
    }
}
