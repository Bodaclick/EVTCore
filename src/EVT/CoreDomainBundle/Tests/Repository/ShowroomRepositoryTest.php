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
    protected $provider;
    protected $vertical;
    protected $showroom;
    protected $asyncDispatcher;
    protected $srMapper;
    protected $emMock;
    protected $metadata;

    public function setUp()
    {
        $this->provider = $this->getMockBuilder('EVT\CoreDomain\Provider\Provider')
            ->disableOriginalConstructor()->getMock();
        $this->vertical = $this->getMockBuilder('EVT\CoreDomain\Provider\Vertical')
            ->disableOriginalConstructor()->getMock();
        $this->showroom = new DomainShowroom($this->provider, $this->vertical, 0);

        $this->srMapper = $this->getMockBuilder('EVT\CoreDomainBundle\Mapping\ShowroomMapping')
            ->disableOriginalConstructor()->getMock();
        $this->srMapper->expects($this->once())->method('mapDomainToEntity')->will($this->returnValue(new Showroom()));

        $this->emMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->emMock->expects($this->once())->method('persist')->will($this->returnSelf());
        $this->emMock->expects($this->once())->method('flush')->will($this->returnSelf());
        $this->metadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()->getMock();

        $this->asyncDispatcher = $this->getMockBuilder(
            'BDK\AsyncDispatcherBundle\Model\EventDispatcher\AsyncEventDispatcher'
        )->disableOriginalConstructor()->getMock();

        $this->asyncDispatcher->expects($this->once())
            ->method('dispatch')->will($this->returnValue($this->returnSelf()));
    }

    public function testSaveCreate()
    {
        $repo = new ShowroomRepository($this->emMock, $this->metadata);
        $repo->setMapper($this->srMapper);
        $repo->setAsyncDispatcher($this->asyncDispatcher);
        $repo->save($this->showroom);
    }

    public function testSaveUpdate()
    {
        $rflShowroom = new \ReflectionClass($this->showroom);
        $rflId = $rflShowroom->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($this->showroom, 1);

        $repo = new ShowroomRepository($this->emMock, $this->metadata);
        $repo->setMapper($this->srMapper);
        $repo->setAsyncDispatcher($this->asyncDispatcher);
        $repo->save($this->showroom);
    }
}
