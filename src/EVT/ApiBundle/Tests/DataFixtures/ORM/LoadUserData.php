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

        $userManager->updateUser($manager);

        $user = $userManager->createUser();
        $user->setUsername('username');
        $user->setEmail('valid@email.com');
        $user->setPlainPassword('pass');
        $user->setName('name');

        $userManager->updateUser($user);
    }
}
