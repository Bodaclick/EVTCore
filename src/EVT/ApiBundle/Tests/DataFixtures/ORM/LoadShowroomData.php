<?php

namespace EVT\ApiBundle\Tests\DataFixtures\ORM;

use EVT\CoreDomain\Provider\ShowroomType;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EVT\CoreDomainBundle\Entity\Showroom;
use EVT\CoreDomainBundle\Entity\Provider;
use EVT\CoreDomainBundle\Entity\Vertical;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadShowroomData implements FixtureInterface, ContainerAwareInterface
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

        $user = $userManager->createUser();
        $user->setUsername('usernameManager');
        $user->setEmail('valid@emailManager.com');
        $user->setPlainPassword('passManager');
        $user->addRole('ROLE_MANAGER');
        $user->setName('nameManager');
        $user->setSurnames('surnamesManager');
        $user->setPhone('0132465987');

        $userManager->updateUser($user);

        $prov = new Provider();
        $prov->setName('name');
        $prov->setNotificationEmails(['valid@email.com']);
        $prov->setLocationLat(10);
        $prov->setLocationLong(10);
        $prov->setLocationAdminLevel1('test');
        $prov->setLocationAdminLevel2('test');
        $prov->setLocationCountry('Spain');
        $prov->setLang('es_ES');
        $prov->addGenericUser($user);
        $manager->persist($prov);

        $vert = new Vertical();
        $vert->setDomain('test.com');
        $manager->persist($vert);

        $showroom = new Showroom();
        $showroom->setProvider($prov);
        $showroom->setVertical($vert);
        $showroom->setType(ShowroomType::FREE); //Free
        $showroom->setScore(0);
        $manager->persist($showroom);

        $vert2 = new Vertical();
        $vert2->setDomain('test2.com');
        $manager->persist($vert2);

        $showroom2 = new Showroom();
        $showroom2->setProvider($prov);
        $showroom2->setVertical($vert2);
        $showroom2->setType(ShowroomType::FREE); //Free
        $showroom2->setScore(0);
        $manager->persist($showroom2);

        $manager->flush();
    }
}
