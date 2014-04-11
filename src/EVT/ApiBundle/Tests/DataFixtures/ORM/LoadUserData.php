<?php

namespace EVT\ApiBundle\Tests\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
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

        $manager = $userManager->createUser();
        $manager->setUsername('usernameManager');
        $manager->setEmail('valid@emailManager.com');
        $manager->setPlainPassword('passManager');
        $manager->addRole('ROLE_MANAGER');
        $manager->setName('nameManager');
        $manager->setSurnames('surnamesManager');
        $manager->setPhone('0132465987');

        $userManager->updateUser($manager);

        $user = $userManager->createUser();
        $user->setUsername('username');
        $user->setEmail('valid@email.com');
        $user->setPlainPassword('pass');
        $user->setName('name');

        $userManager->updateUser($user);

        $manager2 = $userManager->createUser();
        $manager2->setUsername('usernameManager2');
        $manager2->setEmail('valid2@emailManager.com');
        $manager2->setPlainPassword('passManager2');
        $manager2->addRole('ROLE_MANAGER');
        $manager2->setName('nameManager2');
        $manager2->setSurnames('surnamesManager2');
        $manager2->setPhone('0132488887');

        $userManager->updateUser($manager2);

        $employee = $userManager->createUser();
        $employee->setUsername('usernameEmployee');
        $employee->setEmail('employee@bodaclick.com');
        $employee->setPlainPassword('passEmployee');
        $employee->addRole('ROLE_EMPLOYEE');
        $employee->setName('nameEmployee');
        $employee->setSurnames('surnamesEmployee');
        $employee->setPhone('912225588');

        $userManager->updateUser($employee);
    }
}
