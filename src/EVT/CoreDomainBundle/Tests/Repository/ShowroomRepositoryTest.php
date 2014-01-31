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
    public function testSave()
    {
        $provider = $this->getMockBuilder('EVT\CoreDomain\Provider\Provider')->disableOriginalConstructor()->getMock();
        $vertical = $this->getMockBuilder('EVT\CoreDomain\Provider\Vertical')->disableOriginalConstructor()->getMock();
        $domainMock = new DomainShowroom($provider, $vertical, 0);
        $showroom = new Showroom();

        $srMapper  =$this->getMockBuilder('EVT\CoreDomainBundle\Mapping\ShowroomMapping')
            ->disableOriginalConstructor()->getMock();
        $srMapper->expects($this->once())->method('mapDomainToEntity')->will($this->returnValue($showroom));
        $emMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $emMock->expects($this->once())->method('persist')->will($this->returnSelf());
        $emMock->expects($this->once())->method('flush')->will($this->returnSelf());
        $metadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()->getMock();

        $repo = new ShowroomRepository($emMock, $metadata);
        $repo->setMapper($srMapper);
        $repo->save($domainMock);
    }

}
