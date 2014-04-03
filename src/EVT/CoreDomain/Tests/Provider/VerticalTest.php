<?php

namespace EVT\CoreDomain\Tests\Provider;

use EVT\CoreDomain\Provider\ShowroomType;
use EVT\CoreDomain\Email;
use EVT\CoreDomain\EmailCollection;
use EVT\CoreDomain\InformationBag;
use EVT\CoreDomain\Provider\ProviderId;
use EVT\CoreDomain\Provider\Provider;
use EVT\CoreDomain\Provider\Vertical;

/**
 * VerticalTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class VerticalTest extends \PHPUnit_Framework_TestCase
{
    public function testCreation()
    {
        $testName = "Test Name";
        $vertcal = new Vertical($testName, 'es_ES');
        $this->assertEquals($testName, $vertcal->getDomain());
    }

    public function testAddShowroom()
    {
        $vertical = new Vertical("Test Name", 'es_ES');
        $provider = new Provider(
            new ProviderId(''),
            "Test Name",
            new EmailCollection(new Email('valid@email.com')),
            'es_ES'
        );
        $informationBag = new InformationBag();

        $showroom = $vertical->addShowroom($provider, new ShowroomType(ShowroomType::PRO), $informationBag);
        $this->assertInstanceOf('EVT\CoreDomain\Provider\Showroom', $showroom);
        $this->assertEquals(1, $showroom->getScore());
        $this->assertEquals('Test Name', $showroom->getName());
    }

    public function testAddShowroomFullInfo()
    {
        $vertical = new Vertical("Test Name", 'es_ES');
        $provider = new Provider(
            new ProviderId(''),
            "Test Name",
            new EmailCollection(new Email('valid@email.com')),
            'es_ES'
        );
        $informationBag = new InformationBag(['name' => 'myName', 'phone' => '999999999', 'slug' => 'slug']);

        $showroom = $vertical->addShowroom(
            $provider,
            new ShowroomType(ShowroomType::PRO),
            $informationBag,
            'extra_data_test'
        );
        $this->assertInstanceOf('EVT\CoreDomain\Provider\Showroom', $showroom);
        $this->assertEquals(1, $showroom->getScore());
        $this->assertEquals('myName', $showroom->getName());
        $this->assertEquals('999999999', $showroom->getPhone());
        $this->assertEquals('slug', $showroom->getSlug());
        $this->assertEquals('extra_data_test', $showroom->getExtraData());
    }

    public function testReAddShowroom()
    {
        $vertical = new Vertical("Test Name", 'es_ES');
        $provider = new Provider(
            new ProviderId(''),
            "Test Name",
            new EmailCollection(new Email('valid@email.com')),
            'es_ES'
        );
        $provider2 = new Provider(
            new ProviderId(''),
            "Test2",
            new EmailCollection(new Email('valid2@email.com')),
            'es_ES'
        );

        $showroom1 = $vertical->addShowroom($provider, new ShowroomType(ShowroomType::PRO));
        $vertical->addShowroom($provider2, 0);

        $showroom3 = $vertical->addShowroom($provider, new ShowroomType(ShowroomType::PRO));
        $this->assertEquals($showroom1, $showroom3);
    }
}
