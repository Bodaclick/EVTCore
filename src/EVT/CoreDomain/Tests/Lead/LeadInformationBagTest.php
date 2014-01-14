<?php

namespace EVT\CoreDomain\Tests\Lead;

use EVT\CoreDomain\Lead\LeadInformationBag;

class LeadInformationBagTest extends \PHPUnit_Framework_TestCase
{
    public function testUseInterface()
    {
        $this->assertInstanceOf('EVT\CoreDomain\InformationBag', new LeadInformationBag());
    }
}
