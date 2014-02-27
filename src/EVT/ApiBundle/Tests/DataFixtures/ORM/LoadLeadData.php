<?php

namespace EVT\ApiBundle\Tests\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EVT\CoreDomainBundle\Entity\Lead;
use EVT\CoreDomainBundle\Entity\Showroom;
use EVT\CoreDomainBundle\Entity\Provider;
use EVT\CoreDomainBundle\Entity\Vertical;

/**
 * LoadLeadData
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class LoadLeadData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $prov = new Provider();
        $prov->setName('name');
        $prov->setNotificationEmails(['valid@email.com']);
        $prov->setLocationLat(10);
        $prov->setLocationLong(10);
        $prov->setLocationAdminLevel1('test');
        $prov->setLocationAdminLevel2('test');
        $prov->setLocationCountry('Spain');
        $manager->persist($prov);
        $manager->flush();

        $vert = new Vertical();
        $vert->setDomain('test.com');
        $manager->persist($vert);
        $manager->flush();

        $showroom = new Showroom();
        $showroom->setProvider($prov);
        $showroom->setVertical($vert);
        $showroom->setScore(0);
        $manager->persist($showroom);
        $manager->flush();

        $lead = new Lead();
        $lead->setEventDate(new \DateTime('2014-02-20 23:50:26'));
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

        $manager->persist($lead);
        $manager->flush();
    }
}
