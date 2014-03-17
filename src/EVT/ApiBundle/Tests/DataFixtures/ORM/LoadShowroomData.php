<?php

namespace EVT\ApiBundle\Tests\DataFixtures\ORM;

use EVT\CoreDomain\Provider\ShowroomType;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EVT\CoreDomainBundle\Entity\Showroom;
use EVT\CoreDomainBundle\Entity\Provider;
use EVT\CoreDomainBundle\Entity\Vertical;

class LoadShowroomData implements FixtureInterface
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
        $vert = new Vertical();
        $vert->setDomain('test.com');
        $manager->persist($vert);
        $manager->flush();
        $showroom = new Showroom();
        $showroom->setProvider($prov);
        $showroom->setVertical($vert);
        $showroom->setType(ShowroomType::FREE); //Free
        $showroom->setScore(0);
        $manager->persist($showroom);
        $manager->flush();

    }
}
