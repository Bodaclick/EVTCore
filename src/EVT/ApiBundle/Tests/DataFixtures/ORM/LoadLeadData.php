<?php

namespace EVT\ApiBundle\Tests\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EVT\CoreDomainBundle\Model\LeadInformation;
use EVT\CoreDomainBundle\Entity\Lead;
use EVT\CoreDomainBundle\Entity\Showroom;
use EVT\CoreDomainBundle\Entity\Provider;
use EVT\CoreDomainBundle\Entity\Vertical;
use EVT\CoreDomain\Provider\ShowroomType;

/**
 * LoadLeadData
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class LoadLeadData implements FixtureInterface, ContainerAwareInterface
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

        $user2 = $userManager->createUser();
        $user2->setUsername('usernameManager2');
        $user2->setEmail('valid2@emailManager.com');
        $user2->setPlainPassword('passManager2');
        $user2->addRole('ROLE_MANAGER');
        $user2->setName('nameManager2');
        $user2->setSurnames('surnamesManager2');
        $user2->setPhone('0132465987');

        $userManager->updateUser($user2);

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
        $showroom->setType(ShowroomType::FREE);
        $showroom->setScore(0);
        $manager->persist($showroom);

        $leadInformation = new LeadInformation();
        $leadInformation->setKey("observations");
        $leadInformation->setValue("This is great");

        $lead = new Lead();
        $lead->setEventDate(new \DateTime('2014-02-20 23:50:26', new \DateTimeZone('UTC')));
        $lead->setEventLocationAdminLevel1('Madrid');
        $lead->setEventLocationAdminLevel2('Madrid');
        $lead->setEventLocationCountry('Spain');
        $lead->setEventLocationLat('24.45');
        $lead->setEventLocationLong('43.98');
        $lead->setEventType(1);
        $lead->setShowroom($showroom);
        $lead->setUserEmail('valid@email.com');
        $lead->setUserName('Pepe');
        $lead->setUserSurnames('Potamo');
        $lead->setUserPhone('919999999');
        $lead->setCreatedAt(new \DateTime('2013-10-10', new \DateTimeZone('UTC')));
        $lead->setReadAt(new \DateTime('2013-10-12', new \DateTimeZone('UTC')));
        $lead->addLeadInformation($leadInformation);

        $manager->persist($lead);

        $lead2 = new Lead();
        $lead2->setEventDate(new \DateTime('2014-01-25 13:55:56', new \DateTimeZone('UTC')));
        $lead2->setEventLocationAdminLevel1('Madrid');
        $lead2->setEventLocationAdminLevel2('Madrid');
        $lead2->setEventLocationCountry('Spain');
        $lead2->setEventLocationLat('24.45');
        $lead2->setEventLocationLong('43.98');
        $lead2->setEventType(1);
        $lead2->setShowroom($showroom);
        $lead2->setUserEmail('valid@email.com');
        $lead2->setUserName('Pepe');
        $lead2->setUserSurnames('Potamo');
        $lead2->setUserPhone('919999999');
        $lead2->setCreatedAt(new \DateTime('2013-11-11', new \DateTimeZone('UTC')));
        $lead2->setReadAt(new \DateTime('2013-10-01', new \DateTimeZone('UTC')));
        $lead2->addLeadInformation($leadInformation);

        $manager->persist($lead2);

        $manager->flush();
    }
}
