<?php

namespace EVT\CoreDomain\Tests\Provider;

use EVT\CoreDomain\Provider\ShowroomType;
use EVT\CoreDomain\Email;
use EVT\CoreDomain\EmailCollection;
use EVT\CoreDomain\InformationBag;
use EVT\CoreDomain\Provider\ProviderId;
use EVT\CoreDomain\Provider\Showroom;
use EVT\CoreDomain\Provider\Vertical;
use EVT\CoreDomain\Provider\Provider;

/**
 * ShowroomTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class ShowroomTest extends \PHPUnit_Framework_TestCase
{
    public function testCreation()
    {
        $provider = new Provider(
            new ProviderId(''),
            "testName",
            new EmailCollection(new Email('valid@email.com')),
            'es_ES'
        );
        $vertical = new Vertical("testDomain.test");
        $informationBag = new InformationBag(['slug' => 'different']);

        $showroom = new Showroom($provider, $vertical, new ShowroomType(ShowroomType::FREE), $informationBag);
        $this->assertEquals('testDomain.test/different', $showroom->getUrl());
    }

    public function testCreationWithExtraData()
    {
        $provider = new Provider(
            new ProviderId(''),
            "testName",
            new EmailCollection(new Email('valid@email.com')),
            'es_ES'
        );
        $vertical = new Vertical("testDomain.test");
        $informationBag = new InformationBag(['slug' => 'different']);

        $showroom = new Showroom(
            $provider,
            $vertical,
            new ShowroomType(ShowroomType::FREE),
            $informationBag,
            'extra_data_content'
        );
        $this->assertEquals('extra_data_content', $showroom->getExtraData());
    }

    public function testUrlChange()
    {
        $provider = new Provider(
            new ProviderId(''),
            "testName",
            new EmailCollection(new Email('valid@email.com')),
            'es_ES'
        );
        $vertical = new Vertical("testDomain.test");
        $informationBag = new InformationBag();

        $showroom = new Showroom($provider, $vertical, new ShowroomType(ShowroomType::FREE), $informationBag);

        $showroom->changeSlug('newSlug');

        $this->assertEquals('testname', $provider->getSlug());
        $this->assertEquals('testDomain.test/newSlug', $showroom->getUrl());
    }
}
