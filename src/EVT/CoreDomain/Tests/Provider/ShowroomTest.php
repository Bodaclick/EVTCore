<?php
namespace EVT\CoreDomain\Tests\Provider;

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
        $provider = new Provider(new ProviderId(''), "testName");
        $vertical = new Vertical("testDomain.test");
        
        $showroom = new Showroom($provider, $vertical);
        
        $this->assertEquals($provider, $showroom->getProvider());
        $this->assertEquals($vertical, $showroom->getVertical());
    }

    public function testUrlChange()
    {
        $provider = new Provider(new ProviderId(''), "testName");
        $vertical = new Vertical("testDomain.test");
        
        $showroom = new Showroom($provider, $vertical);
        
        $this->assertEquals('testDomain.test/testName', $showroom->getUrl());
        
        $showroom->changeSlug('newSlug');
        
        $this->assertEquals('testName', $showroom->getProvider()->getSlug());
        $this->assertEquals('testDomain.test/newSlug', $showroom->getUrl());
    }
}
