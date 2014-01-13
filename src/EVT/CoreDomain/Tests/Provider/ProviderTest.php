<?php
namespace EVT\CoreDomain\Tests\Provider;

use EVT\CoreDomain\Provider\Provider;

class ProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testProviderCreation()
    {
        $testName = "Test Name";
        $provider = new Provider($testName);
        $this->assertEquals($testName, $provider->getName());
    }
}
