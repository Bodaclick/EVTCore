<?php

namespace EVT\CoreDomainBundle\Test\Repository;

use EVT\CoreDomainBundle\Entity\GenericUser;
use EVT\CoreDomainBundle\Repository\UserRepository;
use EVT\CoreDomain\User\User;
use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\Email;

class UserRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testSaveUser()
    {
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $metadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()->getMock();
        $em->expects($this->once())->method('flush')->will($this->returnValue(null));
        $em->expects($this->once())->method('persist')->will(
            $this->returnCallback(
                function ($entity) {
                    $rflUser = new \ReflectionClass($entity);
                    $rflId = $rflUser->getProperty('id');
                    $rflId->setAccessible(true);
                    $rflId->setValue($entity, 1);
                }
            )
        );
        $userManagerMock = $this->getMockBuilder('FOS\UserBundle\Model\UserManager')
            ->disableOriginalConstructor()->getMock();
        $userManagerMock->expects($this->once())->method('createUser')->will($this->returnValue(new GenericUser()));
        $userManagerMock->expects($this->once())->method('updateUser')->will($this->returnValue(true));

        $asyncDispatcher = $this->getMockBuilder(
            'BDK\AsyncEventDispatcher\AsyncEventDispatcher'
        )->disableOriginalConstructor()->getMock();

        $asyncDispatcher->expects($this->once())
            ->method('dispatch')->will($this->returnValue($this->returnSelf()));

        $vertical = $this->getMockBuilder(
            'EVT\CoreDomain\Provider\Vertical'
        )->disableOriginalConstructor()->getMock();

        $showroom = $this->getMockBuilder(
            'EVT\CoreDomain\Provider\Showroom'
        )->disableOriginalConstructor()->getMock();

        $showroom->expects($this->once())
            ->method('getVertical')->will($this->returnValue($vertical));

        $lead = $this->getMockBuilder(
            'EVT\CoreDomain\Lead\Lead'
        )->disableOriginalConstructor()->getMock();

        $lead->expects($this->once())
            ->method('getShowroom')->will($this->returnValue($showroom));

        $leadRepo = $this->getMockBuilder(
            'EVT\CoreDomainBundle\Repository\LeadRepository'
        )->disableOriginalConstructor()->getMock();

        $leadRepo->expects($this->once())
            ->method('getLastLeadByEmail')->will($this->returnValue($lead));


        $user = new User(new Email('valid@email.com'), new PersonalInformation('a', 'b', 'c'));
        $repo = new UserRepository($em, $metadata);
        $repo->setUserManager($userManagerMock);
        $repo->setAsyncDispatcher($asyncDispatcher);
        $repo->setLeadRepository($leadRepo);
        $repo->save($user);
        $this->assertEquals(1, $user->getId());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testObjectNotUser()
    {
        $object = new \StdClass();
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $metadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()->getMock();
        $repo = new UserRepository($em, $metadata);
        $repo->save($object);
    }
}
