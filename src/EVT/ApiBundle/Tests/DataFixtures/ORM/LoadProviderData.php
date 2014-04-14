<?php

namespace EVT\ApiBundle\Tests\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EVT\CoreDomainBundle\Entity\Showroom;
use EVT\CoreDomainBundle\Entity\Provider;
use EVT\CoreDomainBundle\Entity\Vertical;
use EVT\CoreDomain\Provider\ShowroomType;

/**
 * LoadProviderData
 *
 * @author    Alvaro Prudencio <aprudencio@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class LoadProviderData implements FixtureInterface, ContainerAwareInterface
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

        //Manager User with provider and leads
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
        $prov->setName('name1');
        $prov->setNotificationEmails(['valid@email.com']);
        $prov->setLocationLat(10);
        $prov->setLocationLong(10);
        $prov->setLocationAdminLevel1('test');
        $prov->setLocationAdminLevel2('test');
        $prov->setLocationCountry('Spain');
        $prov->setLang('es_ES');
        $prov->addGenericUser($user);
        $rflProvider = new \ReflectionClass($prov);
        $rflId = $rflProvider->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($prov, 1);
        $manager->persist($prov);

        $vert = new Vertical();
        $vert->setDomain('verticalTest1.com');
        $vert->setLang('es_ES');
        $vert->setTimezone('Europe/Madrid');
        $manager->persist($vert);

        $showroom = new Showroom();
        $showroom->setProvider($prov);
        $showroom->setVertical($vert);
        $showroom->setType(ShowroomType::FREE);
        $showroom->setScore(0);
        $rflShowroom = new \ReflectionClass($showroom);
        $rflId = $rflShowroom->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($showroom, 1);
        $manager->persist($showroom);

        //Manager User without providers
        $user2 = $userManager->createUser();
        $user2->setUsername('usernameManager2');
        $user2->setEmail('valid2@emailManager.com');
        $user2->setPlainPassword('passManager2');
        $user2->addRole('ROLE_MANAGER');
        $user2->setName('nameManager2');
        $user2->setSurnames('surnamesManager2');
        $user2->setPhone('0132465987');
        $userManager->updateUser($user2);

        //Manager User with provider without leads
        $user3 = $userManager->createUser();
        $user3->setUsername('usernameManager3');
        $user3->setEmail('valid@emailManager3.com');
        $user3->setPlainPassword('passManager3');
        $user3->addRole('ROLE_MANAGER');
        $user3->setName('nameManager3');
        $user3->setSurnames('surnamesManager3');
        $user3->setPhone('0132465987');
        $userManager->updateUser($user3);

        $prov2 = new Provider();
        $prov2->setName('name2');
        $prov2->setNotificationEmails(['valid@email.com']);
        $prov2->setLocationLat(10);
        $prov2->setLocationLong(10);
        $prov2->setLocationAdminLevel1('test');
        $prov2->setLocationAdminLevel2('test');
        $prov2->setLocationCountry('Spain');
        $prov2->setLang('es_ES');
        $prov2->addGenericUser($user3);
        $rflProvider = new \ReflectionClass($prov2);
        $rflId = $rflProvider->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($prov2, 2);
        $manager->persist($prov2);

        //Employee User
        $employee = $userManager->createUser();
        $employee->setUsername('usernameEmployee');
        $employee->setEmail('valid@emailEmployee.com');
        $employee->setPlainPassword('passEmployee');
        $employee->addRole('ROLE_EMPLOYEE');
        $employee->setName('nameEmployee');
        $employee->setSurnames('surnamesEmployee');
        $employee->setPhone('0132465987');
        $userManager->updateUser($employee);

        //Another Manager user with provider and leads
        $user4 = $userManager->createUser();
        $user4->setUsername('usernameManager4');
        $user4->setEmail('valid@emailManager4.com');
        $user4->setPlainPassword('passManager4');
        $user4->addRole('ROLE_MANAGER');
        $user4->setName('nameManager4');
        $user4->setSurnames('surnamesManager4');
        $user4->setPhone('0132465987');
        $userManager->updateUser($user4);

        $prov3 = new Provider();
        $prov3->setName('name');
        $prov3->setNotificationEmails(['valid@email.com']);
        $prov3->setLocationLat(10);
        $prov3->setLocationLong(10);
        $prov3->setLocationAdminLevel1('test');
        $prov3->setLocationAdminLevel2('test');
        $prov3->setLocationCountry('Spain');
        $prov3->setLang('es_ES');
        $prov3->addGenericUser($user4);
        $rflProvider = new \ReflectionClass($prov3);
        $rflId = $rflProvider->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($prov3, 3);
        $manager->persist($prov3);

        $vert2 = new Vertical();
        $vert2->setDomain('verticalTest2.com');
        $vert2->setLang('es_ES');
        $vert2->setTimezone('Europe/Madrid');
        $manager->persist($vert2);

        $showroom2 = new Showroom();
        $showroom2->setProvider($prov3);
        $showroom2->setVertical($vert2);
        $showroom2->setType(ShowroomType::FREE);
        $showroom2->setScore(0);
        $rflShowroom = new \ReflectionClass($showroom2);
        $rflId = $rflShowroom->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($showroom2, 2);
        $manager->persist($showroom2);

        $manager->flush();
    }
}
