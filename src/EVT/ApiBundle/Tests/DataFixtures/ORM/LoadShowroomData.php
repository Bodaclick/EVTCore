<?php

namespace EVT\ApiBundle\Tests\DataFixtures\ORM;

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
        $manager->persist($prov);
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

    }
}
