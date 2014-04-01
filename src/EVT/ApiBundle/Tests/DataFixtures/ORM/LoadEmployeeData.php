<?php

namespace EVT\ApiBundle\Tests\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadLeadData
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class LoadEmployeeData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        $employee = $userManager->createUser();
        $employee->setUsername('usernameEmployee');
        $employee->setEmail('valid@emailEmployee.com');
        $employee->setPlainPassword('passEmployee');
        $employee->addRole('ROLE_EMPLOYEE');
        $employee->setName('nameEmployee');
        $employee->setSurnames('surnamesEmployee');
        $employee->setPhone('01');

        $userManager->updateUser($employee);
        $manager->flush();
    }
}
