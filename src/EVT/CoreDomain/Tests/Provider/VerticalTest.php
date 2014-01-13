<?php
namespace EVT\CoreDomain\Tests\Provider;

use EVT\CoreDomain\Provider\Vertical;

class VerticalTest extends \PHPUnit_Framework_TestCase
{
    public function testCreation()
    {
        $testName = "Test Name";
        $vertcal = new Vertical($testName);
        $this->assertEquals($testName, $vertcal->getDomain());
    }
}
