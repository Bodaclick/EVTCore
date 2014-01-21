<?php
namespace EVT\CoreDomain\Tests\Provider;

use EVT\CoreDomain\Email;
use EVT\CoreDomain\EmailCollection;
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
        $provider = new Provider(new ProviderId(''), "testName", new EmailCollection(new Email('valid@email.com')));
        $vertical = new Vertical("testDomain.test");

        $showroom = new Showroom($provider, $vertical);
        $this->assertEquals('testDomain.test/testName', $showroom->getUrl());
    }

    public function testUrlChange()
    {
        $provider = new Provider(new ProviderId(''), "testName", new EmailCollection(new Email('valid@email.com')));
        $vertical = new Vertical("testDomain.test");

        $showroom = new Showroom($provider, $vertical);

        $showroom->changeSlug('newSlug');

        $this->assertEquals('testName', $provider->getSlug());
        $this->assertEquals('testDomain.test/newSlug', $showroom->getUrl());
    }
}
