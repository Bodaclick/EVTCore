<?php

namespace EVT\ApiBundle\Tests\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EVT\CoreDomainBundle\Entity\Provider;
use EVT\CoreDomainBundle\Entity\Vertical;

class LoadVerticalData implements FixtureInterface
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
        $prov->setLang('es_ES');
        $manager->persist($prov);
        $vert = new Vertical();
        $vert->setDomain('test.com');
        $manager->persist($vert);
        $manager->flush();
    }
}
