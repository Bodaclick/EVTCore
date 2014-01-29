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
        $user = new User(new Email('valid@email.com'), new PersonalInformation('a', 'b', 'c'));
        $repo = new UserRepository($em, $metadata);
        $repo->setUserManager($userManagerMock);
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
