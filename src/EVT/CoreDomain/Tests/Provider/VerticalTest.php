<?php
namespace EVT\CoreDomain\Tests\Provider;

use EVT\CoreDomain\Email;
use EVT\CoreDomain\EmailCollection;
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
        $vertcal = new Vertical($testName);
        $this->assertEquals($testName, $vertcal->getDomain());
    }

    public function testAddShowroom()
    {
        $vertical = new Vertical("Test Name");
        $provider = new Provider(new ProviderId(''), "Test Name", new EmailCollection(new Email('valid@email.com')));

        $showroom = $vertical->addShowroom($provider, 1);
        $this->assertInstanceOf('EVT\CoreDomain\Provider\Showroom', $showroom);
        $this->assertEquals(1, $showroom->getScore());
    }

    public function testReAddShowroom()
    {
        $vertical = new Vertical("Test Name");
        $provider = new Provider(new ProviderId(''), "Test Name", new EmailCollection(new Email('valid@email.com')));
        $provider2 = new Provider(new ProviderId(''), "Test2", new EmailCollection(new Email('valid2@email.com')));

        $showroom1 = $vertical->addShowroom($provider, 1);
        $vertical->addShowroom($provider2, 0);

        $showroom3 = $vertical->addShowroom($provider, 1);
        $this->assertEquals($showroom1, $showroom3);
    }
}
