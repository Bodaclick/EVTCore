<?php

namespace EVT\ApiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EVT\CoreDomainBundle\Entity\Showroom;
use EVT\CoreDomainBundle\Entity\Provider;
use EVT\CoreDomainBundle\Entity\Vertical;
use EVT\CoreDomain\EmailCollection;
use EVT\CoreDomain\Email;
use EVT\CoreDomain\Provider\Provider as DProvider;
use EVT\CoreDomain\Provider\ProviderId;
use EVT\CoreDomain\Provider\Vertical as DVertical;

class LoadShowroomData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $prov = new Provider();
        $prov->setName('name');
        $manager->persist($prov);
        $vert = new Vertical();
        $vert->setDomain('test.com');
        $manager->persist($vert);
        $manager->flush();
        $showroom = new Showroom(
            new DProvider(new ProviderId($prov->getId()), 'name', new EmailCollection(new Email('test@email.com'))),
            new DVertical('test.com'),
            0
        );
        $showroom->setProvider($prov);
        $showroom->setVertical($vert);
        $showroom->setScore(0);
        $manager->persist($showroom);
        $manager->flush();

    }
}
