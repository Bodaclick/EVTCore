<?php
namespace EVT\CoreDomain\Tests\Provider;

use EVT\CoreDomain\Provider\Provider;

class ShowroomCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreation()
    {
        $testName = "Test Name";
        $provider = new Provider($testName);
        $this->assertEquals($testName, $provider->getName());
    }
}
